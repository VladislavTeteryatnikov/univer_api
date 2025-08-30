<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class ClassService
{
    /**
     * @return SupportCollection
     */
    public function getAllClasses(): SupportCollection
    {
        return ClassModel::query()->orderBy('id')->pluck('name', 'id');
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getClassWithStudents(int $id)
    {
        $class = ClassModel::with('students')->find($id);

        if (!$class) {
            return null;
        }

        return [
            'class_name' => $class->name,
            'students' => $this->getStudentsByClass($class->students),
        ];
    }

    /**
     * @param Collection $students
     * @return array
     */
    private function getStudentsByClass(Collection $students): array
    {
        return $students->map(function ($student) {
            return [
                'name' => $student->name,
                'email' => $student->email,
            ];
        })->toArray();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getClassStudyPlan(int $id): ?array
    {
        $class = ClassModel::with('lectures')->find($id);

        if (!$class) {
            return null;
        }

        return [
            'class_name' => $class->name,
            'lectures' => $this->getLecturesByClass($class->lectures),
        ];
    }

    /**
     * @param Collection $lectures
     * @return array
     */
    private function getLecturesByClass(Collection $lectures): array
    {
        return $lectures
            ->sortBy('pivot.order')
            ->map(function ($lecture) {
            return [
                'title' => $lecture->title,
                'description' => $lecture->description,
                'order' => $lecture->pivot->order,
                'completed' => (bool) $lecture->pivot->completed,
            ];
            })->values()
            ->toArray();
    }

    /**
     * @param int $classId
     * @param array $lectures
     * @return bool
     */
    public function updateClassStudyPlan(int $classId, array $lectures): bool
    {
        $class = ClassModel::find($classId);

        if (!$class) {
            return false;
        }

        $syncData = $this->prepareDataForUpdateStudyPlan($lectures);
        $class->lectures()->sync($syncData);
        return true;
    }

    /**
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
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateClass(int $id, array $data): ?array
    {
        $class = ClassModel::find($id);

        if (!$class) {
            return null;
        }

        $class->update($data);

        return [
            'class_name' => $class->name
        ];
    }

    public function removeClass(int $id): bool
    {
        $class = ClassModel::find($id);

        if (!$class) {
            return false;
        }

        return $class->delete();
    }
}
