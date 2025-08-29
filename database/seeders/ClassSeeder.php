<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            '9-A',
            '9-B',
            '10-A',
            '10-B',
            '11-A',
            '11-B',
        ];

        foreach ($classes as $className) {
            ClassModel::firstOrCreate(['name' => $className]);
        }
    }
}
