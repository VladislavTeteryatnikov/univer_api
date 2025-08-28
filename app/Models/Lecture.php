<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = ['title', 'description'];

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'classes_lectures');
    }
}
