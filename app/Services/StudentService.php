<?php

namespace App\Services;

use App\Models\Student;

class StudentService
{
    public function __construct(private ClassService $classService) {}

    /**
     * Получить всех студентов: id, name
     *
     * @return array
     */
    public function getAllStudents(): array
    {
        return Student::query()
            ->orderBy('id')
            ->get(['id', 'name'])
            ->toArray();
    }

    /**
     * Получить инфо о студенте, включая класс, если есть, и пройденные лекции (completed true)
     *
     * @param int $id
     * @return array|null
     */
    public function getStudentInfo(int $id): ?array
    {
        $student = Student::with('class.lectures')->find($id);

        if ($student === null) {
            return null;
        }

        return [
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class ?
                [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                ]
                : null,
            'lectures' => $student->class
                ? $this->classService->getCompletedLectures($student->class)
                : []
        ];
    }

    /**
     * Создать студента
     *
     * @param array $data
     * @return array
     */
    public function createStudent(array $data): array
    {
        $student = Student::create($data);
        $student->load('class');

        return [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class ?
                [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                ]
                : null
        ];
    }

    /**
     * Обновить инфо студента
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateStudent(int $id, array $data): ?array
    {
        $student = Student::find($id);

        if ($student === null) {
            return null;
        }

        $student->update($data);
        $student->load('class');

        return [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'class' => $student->class ?
                [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                ]
                : null
        ];
    }

    /**
     * Удалить студента
     *
     * @param int $id
     * @return bool
     */
    public function removeStudent(int $id): bool
    {
        $student = Student::find($id);

        if ($student === null) {
            return false;
        }
        return $student->delete();
    }
}
