<?php

namespace App\Http\Resources\Classes;

use App\Http\Resources\Lectures\LectureIndexResource;
use App\Http\Resources\Students\StudentIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            'students' => $this->whenLoaded('students', function () {
                return StudentIndexResource::collection(
                    $this->students->sortBy('id')
                );
            }),

            'lectures' => $this->whenLoaded('lectures', function () {
                return LectureIndexResource::collection(
                    $this->lectures->sortBy('pivot.order')
                );
            }),
        ];
    }
}
