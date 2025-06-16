<?php
// En database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $itDepartment = Department::where('name', 'Tecnologías de la Información')->first();
        $financeDepartment = Department::where('name', 'Finanzas')->first();

        if ($itDepartment) {
            User::firstOrCreate(
                ['email' => 'it.user@example.com'],
                [
                    'name' => 'Admin User IT',
                    'password' => Hash::make('password'),
                    'department_id' => $itDepartment->id,
                    'role' => 'admin', // ASIGNAR ROL DE ADMIN
                ]
            );
        }

        if ($financeDepartment) {
            User::firstOrCreate(
                ['email' => 'finance.user@example.com'],
                [
                    'name' => 'Finance User',
                    'password' => Hash::make('password'),
                    'department_id' => $financeDepartment->id,
                    'role' => 'user', // ASIGNAR ROL DE USER (o simplemente omitirlo para usar el default)
                ]
            );
        }
    }
}
