<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Classroom;
use App\Models\Master\AcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Ambil atau bikin tahun ajaran 2025/2026
        $academicYear = AcademicYear::firstOrCreate([
            'year_range' => '2025/2026',
        ]);

        // Bikin classroom
        Classroom::firstOrCreate([
            'academic_year_id' => $academicYear->id,
            'name' => 'Kelompok B2',
        ]);
    }
}
