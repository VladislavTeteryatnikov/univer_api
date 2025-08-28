<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'email', 'class_id'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}
