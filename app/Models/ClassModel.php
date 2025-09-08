<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function lectures()
    {
        return $this->belongsToMany(
            Lecture::class,
            'classes_lectures',
        'class_id',
            'lecture_id'
        )->withPivot(['order', 'completed']);
    }

    /**
     * Аксессор: пройденные лекции, отсортированные по порядку
     */
    protected function completedLectures(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->relationLoaded('lectures')) {
                    return [];
                }

                return $this->lectures
                    ->where('pivot.completed', true)
                    ->sortBy('pivot.order')
                    ->map(function ($lecture) {
                        return [
                            'id' => $lecture->id,
                            'title' => $lecture->title
                        ];
                    })
                    ->values()
                    ->all();
            }
        );
    }


}
