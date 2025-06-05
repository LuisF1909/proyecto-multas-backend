<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Importa el modelo User
use App\Models\Department; // Importa el modelo Department
use Illuminate\Support\Facades\Hash; // Para encriptar contraseñas

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos departamentos para asignar usuarios
        $itDepartment = Department::where('name', 'Tecnologías de la Información')->first();
        $financeDepartment = Department::where('name', 'Finanzas')->first();

        if ($itDepartment) {
            User::firstOrCreate(
                ['email' => 'it.user@example.com'], // Condición para buscar
                [ // Datos para crear si no existe
                    'name' => 'Usuario IT Principal',
                    'password' => Hash::make('password'), // ¡Usa contraseñas seguras en producción!
                    'department_id' => $itDepartment->id,
                ]
            );
        }

        if ($financeDepartment) {
            User::firstOrCreate(
                ['email' => 'finance.user@example.com'],
                [
                    'name' => 'Usuario Finanzas Principal',
                    'password' => Hash::make('password'),
                    'department_id' => $financeDepartment->id,
                ]
            );
            User::firstOrCreate(
                ['email' => 'another.finance@example.com'],
                [
                    'name' => 'Otro Usuario Finanzas',
                    'password' => Hash::make('password'),
                    'department_id' => $financeDepartment->id,
                ]
            );
        }

        // Puedes añadir más usuarios aquí si lo deseas
    }
}
