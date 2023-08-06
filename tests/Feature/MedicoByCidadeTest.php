<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Medico;
use App\Models\Cidades;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicoByCidadeTest extends TestCase
{
    use RefreshDatabase;

    public function testGetMedicosByCidadeSuccess()
    {
        // Create a fake city
        $cidade = Cidades::factory()->create();

        // Create fake doctors associated with the city
        $medicos = Medico::factory()->count(3)->create(['cidade_id' => $cidade->id]);

        // Send a GET request to the /cidades/{id_cidade}/medicos route
        $response = $this->get("/api/cidades/{$cidade->id}/medicos");

        // Assert that the response status code is 200 (success)
        $response->assertStatus(200);

        // Extract specific columns from the collection
        $expectedData = $medicos->map(function ($medico) {
            return [
                'id' => $medico->id,
                'nome' => $medico->nome,
                'especialidade' => $medico->especialidade,
                'cidade_id' => $medico->cidade_id,
            ];
        });

        // Assert that the response JSON matches the expected data
        $response->assertExactJson($expectedData->toArray());
    }

    public function testGetMedicosByCidadeNotFound()
    {
        // Send a GET request to the /cidades/{id_cidade}/medicos route with a non-existent city ID
        $response = $this->get('/api/cidades/9999/medicos');

        // Assert that the response status code is 404 (not found)
        $response->assertStatus(404);

        // Assert that the response JSON contains an error message indicating city not found
        $response->assertJson([
            'error' => 'not found',
        ]);
    }
}
