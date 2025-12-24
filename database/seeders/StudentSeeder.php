<?php

namespace Database\Seeders;

use App\Models\Master\Student;
use App\Models\Master\Classroom;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokalisasi Indonesia

        // Pastikan Kelas dengan ID 1 ada untuk menghindari error foreign key
        $classroom = Classroom::find(1);

        if (!$classroom) {
            $this->command->error("Kelas dengan ID 1 tidak ditemukan! Buat kelas terlebih dahulu.");
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            // 1. Buat data siswa random
            $gender = $faker->randomElement(['L', 'P']);
            
            $student = Student::create([
                'name' => $faker->name($gender == 'L' ? 'male' : 'female'),
                'nisn' => $faker->unique()->numerify('00########'), // Format NISN 10 digit
                'dob'  => $faker->date('Y-m-d', '2020-12-31'), // Tanggal lahir random (anak PAUD)
                'gender' => $gender,
            ]);

            // 2. Masukkan siswa ke kelas ID 1 melalui tabel pivot
            $student->classrooms()->attach(1);
        }

        $this->command->info("Berhasil membuat 10 siswa random dan memasukkannya ke Kelas ID 1.");
    }
}
