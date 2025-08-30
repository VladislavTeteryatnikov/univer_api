<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('students', StudentController::class);

Route::apiResource('classes', ClassController::class);

// Доп маршруты для учебного плана
Route::get('/classes/{id}/lectures', [ClassController::class, 'studyPlan']);
Route::put('/classes/{id}/lectures', [ClassController::class, 'updateStudyPlan']);


