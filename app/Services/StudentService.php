<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class StudentService
{
    /**
     * @return Collection
     */
    public function getAllStudents(): SupportCollection
    {
        return Student::query()->pluck('name', 'id');
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getStudentInfo(int $id): ?array
    {
        $student = Student::with('class.lectures')
            ->find($id);

        if (!$student) {
            return null;
        }

        return [
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class->name,
            'lectures' => $this->getCompletedLectures($student->class),
        ];
    }

    /**
     * @param ClassModel $class
     * @return array
     */
    private function getCompletedLectures(ClassModel $class): array
    {
        return $class->lectures
            ->where('pivot.completed', true)
            ->sortBy('pivot.order')
            ->map(function ($lecture) {
                return [
                    'title' => $lecture->title,
                    'description' => $lecture->description,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function createStudent(array $data): array
    {
        $student = Student::create($data);

        return [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class?->name,
        ];
    }

    /**
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateStudent(int $id, array $data): ?array
    {
        $student = Student::find($id);

        if (!$student) {
            return null;
        }

        $student->update($data);

        return [
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class->name,
        ];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeStudent(int $id): bool
    {
        $student = Student::find($id);

        if (!$student) {
            return false;
        }
        return $student->delete();
    }
}
