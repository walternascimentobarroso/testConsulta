<?php

namespace Database\Seeders;

use App\Models\MedicoPaciente;
use Illuminate\Database\Seeder;

class MedicoPacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MedicoPaciente::factory()->count(200)->create();
    }
}
