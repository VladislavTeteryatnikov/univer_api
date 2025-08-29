<?php

namespace Database\Seeders;

use App\Models\Lecture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lecture::query()->delete();
        Lecture::factory()->count(10)->create();
    }
}
