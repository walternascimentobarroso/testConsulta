<?php

namespace Database\Factories;

use App\Models\Cidades;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cidades>
 */
class CidadesFactory extends Factory
{
    protected $model = Cidades::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->city,
            'estado' => $this->faker->state,
        ];
    }
}
