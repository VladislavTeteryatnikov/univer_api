<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Lecture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesLecturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ClassModel::all();
        $lectures = Lecture::all();

        if ($classes->isEmpty() || $lectures->isEmpty()) {
            $this->command->error('Классы или лекции не заполнены');
        }

        $classes->each(function ($class) use ($lectures) {
            $lectures = $lectures->shuffle()->take(rand(5, 8));
            $completedCount = rand(0, $lectures->count());
            $order = 0;

            foreach ($lectures as $lecture) {
                $completed = $order < $completedCount;

                DB::table('classes_lectures')->insert([
                        'class_id' => $class->id,
                        'lecture_id' => $lecture->id,
                        'order' => $order++,
                        'completed' => $completed
                ]);
            }
        });

    }
}
