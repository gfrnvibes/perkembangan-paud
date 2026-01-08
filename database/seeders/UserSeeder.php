<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 1. Buat / ambil role super_admin
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // 2. Buat / ambil user super admin
        $user = User::firstOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ],
        );

        // 3. Assign role (idempotent)
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role);
        }

        // =========================
        // TEACHER (1 orang)
        // =========================
        $teacher = User::create([
            'name' => 'Guru Nurul Amin',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
        ]);

        $teacher->assignRole('teacher');

        // =========================
        // PARENTS (5 orang)
        // masing-masing punya 2 anak
        // student id: 1 - 10
        // =========================
        $parents = [
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'phone' => '081111111111',
                'address' => 'Jl. Kenanga No. 1, Bandung',
                'children' => [1, 2],
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@gmail.com',
                'phone' => '082222222222',
                'address' => 'Jl. Melati No. 2, Surabaya',
                'children' => [3, 4],
            ],
            [
                'name' => 'Rina Lestari',
                'email' => 'rina@gmail.com',
                'phone' => '083333333333',
                'address' => 'Jl. Mawar No. 3, Yogyakarta',
                'children' => [5, 6],
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi@gmail.com',
                'phone' => '084444444444',
                'address' => 'Jl. Anggrek No. 4, Semarang',
                'children' => [7, 8],
            ],
            [
                'name' => 'Nur Aisyah',
                'email' => 'aisyah@gmail.com',
                'phone' => '085555555555',
                'address' => 'Jl. Teratai No. 5, Malang',
                'children' => [9, 10],
            ],
        ];

        foreach ($parents as $parentData) {
            $parent = User::create([
                'name' => $parentData['name'],
                'email' => $parentData['email'],
                'password' => Hash::make('password'),
                'phone' => $parentData['phone'],
                'address' => $parentData['address'],
            ]);

            $parent->assignRole('parent');

            // attach children (students)
            $parent->children()->attach($parentData['children']);
        }
    }
}
