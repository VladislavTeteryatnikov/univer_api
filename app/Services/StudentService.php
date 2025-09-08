<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class StudentService
{
    /**
     * Получить всех студентов
     *
     * @return Collection
     */
    public function getAllStudents(): Collection
    {
        return Student::query()
            ->orderBy('id')
            ->get();
    }

    /**
     * Получить инфо о студенте, включая класс и лекции
     *
     * @param int $id
     * @return Student|null
     */
    public function getStudentInfo(int $id): ?Student
    {
        $student = Student::with('class.lectures')->find($id);

        if ($student === null) {
            return null;
        }
        return $student;
    }

    /**
     * Создать студента
     *
     * @param array $data
     * @return Student
     */
    public function createStudent(array $data): Student
    {
        $student = Student::create($data);
        $student->load('class');
        return $student;
    }

    /**
     * Обновить инфо студента
     *
     * @param int $id
     * @param array $data
     * @return Student|null
     */
    public function updateStudent(int $id, array $data): ?Student
    {
        $student = Student::find($id);

        if ($student === null) {
            return null;
        }

        $student->update($data);
        $student->load('class');
        return $student;
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
