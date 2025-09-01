<?php

namespace App\Services;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Collection;

class LectureService
{
    /**
     * Получить все лекции: id, title
     *
     * @return array
     */
    public function getAllLectures(): array
    {
        return Lecture::query()
            ->orderBy('id')
            ->get(['id', 'title'])
            ->toArray();
    }

    /**
     * Получить лекцию классами, где пройена и их студентами
     *
     * @param int $id
     * @return array|null
     */
    public function getLectureWithDetails(int $id): ?array
    {
        $lecture = Lecture::with('classes.students')->find($id);

        if ($lecture === null) {
            return null;
        }

        return [
            'id' => $lecture->id,
            'title' => $lecture->title,
            'description' => $lecture->description,
            'classes' => $this->formatCompetedClassesWithStudents($lecture->classes),
        ];
    }

    /**
     * Форматирует данные. Фильрует классы, где пройдена лекция с их студентами
     *
     * @param Collection $classes
     * @return array
     */
    private function formatCompetedClassesWithStudents(Collection $classes): array
    {
        return $classes
            ->where('pivot.completed', true)
            ->map(function($class) {
                return [
                    'class_id' => $class->id,
                    'class_name' => $class->name,
                    'students' => $class->students
                        ->sortBy('id')
                        ->map(function ($student) {
                            return [
                                'id' => $student->id,
                                'name' => $student->name,
                            ];
                        })
                        ->toArray()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Создать лекцию
     *
     * @param array $data
     * @return array
     */
    public function createLecture(array $data): array
    {
        $lecture = Lecture::create($data);

        return [
            'id' => $lecture->id,
            'title' => $lecture->title,
            'description' => $lecture->description,
        ];
    }

    /**
     * Обновить лекцию
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateLecture(int $id, array $data): ?array
    {
        $lecture = Lecture::find($id);

        if ($lecture === null) {
            return null;
        }

        $lecture->update($data);

        return [
            'id' => $lecture->id,
            'title' => $lecture->title,
            'description' => $lecture->description,
        ];
    }

    /**
     * Удалить лекцию
     *
     * @param int $id
     * @return bool
     */
    public function removeLecture(int $id): bool
    {
        $lecture = Lecture::find($id);

        if ($lecture === null) {
            return false;
        }

        return $lecture->delete();
    }
}
