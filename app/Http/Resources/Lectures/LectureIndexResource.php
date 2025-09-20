<?php

namespace App\Http\Resources\Lectures;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LectureIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hasPivot = isset($this->pivot) && isset($this->pivot->order);

        return [
            'id' => $this->id,
            'title' => $this->title,

            'order' => $this->when($hasPivot, fn() => $this->pivot->order),
            'completed' => $this->when($hasPivot, fn() => (bool) $this->pivot->completed),
        ];
    }
}
