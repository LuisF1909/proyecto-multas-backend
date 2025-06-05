<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama a los seeders que acabas de crear en el orden deseado
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            // Aquí podrías llamar a MultaSeeder si lo creas en el futuro
        ]);
    }
}
