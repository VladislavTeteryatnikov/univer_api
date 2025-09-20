<?php

namespace App\Http\Resources\Students;

use App\Http\Resources\Classes\ClassResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentShowResource extends JsonResource
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
            'email' => $this->email,
            'class' => $this->whenLoaded('class', function () {
                return new ClassResource($this->class);
            }),
            'lectures' => $this->whenLoaded('class', function () {
                return $this->class->completed_lectures;
            }),
        ];
    }
}
