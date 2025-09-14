<?php

namespace App\Http\Resources\Lectures;

use App\Http\Resources\Classes\ClassIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureShowResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'classes' => $this->whenLoaded('classes', function () {
                return ClassIndexResource::collection(
                    $this->classes->where('pivot.completed', true)
                );
            })
        ];
    }
}
