<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // SUPER ADMIN
        // =========================
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

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
            'email_verified_at' => now(),
        ]);

        $teacher->assignRole('teacher');

        // =========================
        // PARENTS (5 orang)
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
                'email_verified_at' => now(),
            ]);

            $parent->assignRole('parent');
            $parent->children()->attach($parentData['children']);
        }
    }
}
