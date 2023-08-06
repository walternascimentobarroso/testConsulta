<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MedicoSeeder;
use Database\Seeders\CidadesSeeder;
use Database\Seeders\PacienteSeeder;
use Database\Seeders\MedicoPacienteSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            CidadesSeeder::class,
            PacienteSeeder::class,
            MedicoSeeder::class,
            MedicoPacienteSeeder::class,
            UserSeeder::class,
        ]);
    }
}
