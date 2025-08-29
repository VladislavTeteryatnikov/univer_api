<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'class_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}
