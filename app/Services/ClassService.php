<?php

namespace App\Services;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Collection;

class ClassService
{
    /**
     * Получить все классы, отсортированные по id
     *
     * @return Collection
     */
    public function getAllClasses(): Collection
    {
        return ClassModel::query()
            ->orderBy('id')
            ->get();
    }

    /**
     * Получить инфо о классе, включая его студентов
     *
     * @param int $id
     * @return ClassModel|null
     */
    public function getClassWithStudents(int $id): ?ClassModel
    {
        $classWithStudents = ClassModel::with('students')->find($id);

        if ($classWithStudents === null) {
            return null;
        }

        return $classWithStudents;
    }


    /**
     * Получить учебный план класса: лекции с order и completed
     *
     * @param int $id
     * @return ClassModel|null
     */
    public function getClassStudyPlan(int $id): ?ClassModel
    {
        $classWithStudyPlan = ClassModel::with('lectures')->find($id);

        if ($classWithStudyPlan === null) {
            return null;
        }

        return $classWithStudyPlan;
    }

    /**
     * Обновить учебный план класса
     *
     * @param int $classId
     * @param array $lectures
     * @return ClassModel
     */
    public function updateClassStudyPlan(int $classId, array $lectures): ?ClassModel
    {
        $class = ClassModel::find($classId);

        if ($class === null) {
            return null;
        }

        $syncData = $this->prepareDataForUpdateStudyPlan($lectures);
        $class->lectures()->sync($syncData);
        $class->load('lectures');

        return $class;
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
     * @return ClassModel
     */
    public function createClass(array $data): ClassModel
    {
        $class = ClassModel::create($data);

        return $class;
    }

    /**
     * Обновить класс
     *
     * @param int $id
     * @param array $data
     * @return ClassModel|null
     */
    public function updateClass(int $id, array $data): ?ClassModel
    {
        $class = ClassModel::find($id);

        if ($class === null) {
            return null;
        }

        $class->update($data);
        return $class;
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
}
