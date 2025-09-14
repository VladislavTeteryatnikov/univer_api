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
    public function getAllLectures(): Collection
    {
        return Lecture::query()
            ->orderBy('id')
            ->get();
    }

    /**
     * Получить лекцию с классами, где пройдена и их студентами
     *
     * @param int $id
     * @return array|null
     */
    public function getLectureWithDetails(int $id): ?Lecture
    {
        $lecture = Lecture::with('classes.students')->find($id);

        if ($lecture === null) {
            return null;
        }

        return $lecture;
    }

    /**
     * Создать лекцию
     *
     * @param array $data
     * @return array
     */
    public function createLecture(array $data): Lecture
    {
        $lecture = Lecture::create($data);

        return $lecture;
    }

    /**
     * Обновить лекцию
     *
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updateLecture(int $id, array $data): ?Lecture
    {
        $lecture = Lecture::find($id);

        if ($lecture === null) {
            return null;
        }

        $lecture->update($data);

        return $lecture;
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
