<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // El esquema CUP usa tablas propias (persona, usuario, rol, etc.).
        // Agrega seeders específicos aquí cuando los necesites.
    }
}
