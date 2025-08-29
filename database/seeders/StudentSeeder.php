<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ClassModel::all();

        if ($classes->isEmpty()) {
            $this->command->error('Классы для студентов отсутствуют');
            return;
        }

        Student::query()->delete();

        Student::factory()
            ->count(50)
            ->state(fn() => ['class_id' => $classes->random()->id])
            ->create();
    }
}
