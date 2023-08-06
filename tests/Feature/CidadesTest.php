<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cidades;

class CidadesTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCidadesSuccess()
    {
        // Create some cities using the Cidade model factory (if you have one)
        $cidades = Cidades::factory()->count(3)->create();

        // Send a GET request to the /cidades route
        $response = $this->get('/api/cidades');

        // Assert that the response status code is 200 (success)
        $response->assertStatus(200);

        // Assert that the response JSON contains the expected cities data
        $response->assertJson([
            [
                'id' => $cidades[0]->id,
                'nome' => $cidades[0]->nome,
                'estado' => $cidades[0]->estado,
            ],
            [
                'id' => $cidades[1]->id,
                'nome' => $cidades[1]->nome,
                'estado' => $cidades[1]->estado,
            ],
            [
                'id' => $cidades[2]->id,
                'nome' => $cidades[2]->nome,
                'estado' => $cidades[2]->estado,
            ],
        ]);
    }
}
