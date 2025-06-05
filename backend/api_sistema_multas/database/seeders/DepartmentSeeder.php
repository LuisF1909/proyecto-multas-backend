<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department; // Importa el modelo Department

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea departamentos solo si no existen
        Department::firstOrCreate(['name' => 'Finanzas']);
        Department::firstOrCreate(['name' => 'Recursos Humanos']);
        Department::firstOrCreate(['name' => 'Tecnologías de la Información']);
        Department::firstOrCreate(['name' => 'Ventas']);
        Department::firstOrCreate(['name' => 'Operaciones']);
    }
}
