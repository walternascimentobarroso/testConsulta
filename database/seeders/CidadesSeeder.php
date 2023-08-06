<?php

namespace Database\Seeders;

use App\Models\Cidades;
use Illuminate\Database\Seeder;

class CidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cidades::factory()->count(50)->create();
    }
}
