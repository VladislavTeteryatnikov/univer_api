<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];
    protected $hidden = ['created_at', 'updated_at'];

    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'classes_lectures',
            'lecture_id',
            'class_id',
        )->withPivot(['order', 'completed']);
    }
}
