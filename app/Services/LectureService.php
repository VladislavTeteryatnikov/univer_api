<?php

namespace App\Services;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class LectureService
{
    /**
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
            'title' => $lecture->title,
            'description' => $lecture->description,
            'classes' => $this->getClassesWithStudents($lecture->classes),
        ];
    }

    private function getClassesWithStudents(Collection $classes): array
    {
        return $classes
            ->where('pivot.completed', true)
            ->map(function($class) {
                return [
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
            'title' => $lecture->title,
            'description' => $lecture->description,
        ];
    }

    /**
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
