<?php

namespace Database\Factories;

use App\Models\MedicoPaciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicoPaciente>
 */
class MedicoPacienteFactory extends Factory
{

    protected $model = MedicoPaciente::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medico_id' => function () {
                return \App\Models\Medico::factory()->create()->id;
            },
            'paciente_id' => function () {
                return \App\Models\Paciente::factory()->create()->id;
            },
        ];
    }
}
