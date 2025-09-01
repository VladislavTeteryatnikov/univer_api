<?php

namespace App\Services;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Collection;

class ClassService
{
    /**
     * Получить все классы: id, name
     *
     * @return array
     */
    public function getAllClasses(): array
    {
        return ClassModel::query()
            ->orderBy('id')
            ->get(['id', 'name'])
            ->toArray();
    }

    /**
     * Получить инфо о классе, включая его студентов
     *
     * @param int $id
     * @return array|null
     */
    public function getClassWithStudents(int $id): ?array
    {
        $class = ClassModel::with('students')->find($id);

        if ($class === null) {
            return null;
        }

        return [
            'class_id' => $class->id,
            'class_name' => $class->name,
            'students' => $class->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                ];
            })->toArray()
        ];
    }


    /**
     * Получить учебный план класса: лекции с order и completed
     *
     * @param int $id
     * @return array|null
     */
    public function getClassStudyPlan(int $id): ?array
    {
        $class = ClassModel::with('lectures')->find($id);

        if ($class === null) {
            return null;
        }

        return [
            'class_id' => $class->id,
            'class_name' => $class->name,
            'lectures' => $this->prepareLecturesForStudyPlan($class->lectures),
        ];
    }

    /**
     * Подготовить лекции для показа в учебном плане
     *
     * @param Collection $lectures
     * @return array
     */
    private function prepareLecturesForStudyPlan(Collection $lectures): array
    {
        return $lectures
            ->sortBy('pivot.order')
            ->map(function ($lecture) {
                return [
                    'id' => $lecture->id,
                    'title' => $lecture->title,
                    'order' => $lecture->pivot->order,
                    'completed' => (bool) $lecture->pivot->completed,
                ];
            })->values()
            ->toArray();
    }

    /**
     * Обновить учебный план класса
     *
     * @param int $classId
     * @param array $lectures
     * @return bool
     */
    public function updateClassStudyPlan(int $classId, array $lectures): bool
    {
        $class = ClassModel::find($classId);

        if ($class === null) {
            return false;
        }

        $syncData = $this->prepareDataForUpdateStudyPlan($lectures);
        $class->lectures()->sync($syncData);

        return true;
    }

    /**
     * Подготовить данные для sync() в формате [lecture_id => [order, completed]]
     *
     * @param array $lectures
     * @return array
     */
    private function prepareDataForUpdateStudyPlan(array $lectures): array
    {
        $syncData = [];
        foreach ($lectures as $lecture) {
            $syncData[$lecture['id']] = [
                'order' => $lecture['order'],
                'completed' => $lecture['completed'],
            ];
        }
        return $syncData;
    }

    /**
     * Создать класс
     *
     * @param array $data
     * @return array
     */
    public function createClass(array $data): array
    {
        $class = ClassModel::create($data);

        return [
            'id' => $class->id,
            'class_name' => $class->name,
        ];
    }

    /**
     * Обновить класс
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateClass(int $id, array $data): ?array
    {
        $class = ClassModel::find($id);

        if ($class === null) {
            return null;
        }

        $class->update($data);

        return [
            'id' => $class->id,
            'class_name' => $class->name
        ];
    }

    /**
     * Удалить класс
     *
     * @param int $id
     * @return bool
     */
    public function removeClass(int $id): bool
    {
        $class = ClassModel::find($id);

        if ($class === null) {
            return false;
        }

        return $class->delete();
    }

    /**
     * Получить пройденные лекции класса (completed true)
     *
     * @param ClassModel $class
     * @return array
     */
    public function getCompletedLectures(ClassModel $class): array
    {
        return $class->lectures
            ->where('pivot.completed', true)
            ->sortBy('pivot.order')
            ->map(function ($lecture) {
                return [
                    'id' => $lecture->id,
                    'title' => $lecture->title,
                ];
            })
            ->values()
            ->toArray();
    }
}
