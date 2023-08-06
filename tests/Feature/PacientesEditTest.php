<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PacientesEditTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function getAuthToken()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Generate the JWT token for the user
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    public function testUpdatePacienteSuccess()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a new patient in the database
        $paciente = Paciente::factory()->create();

        // Create a fake data array for updating the patient
        $data = [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a PUT request to the /api/pacientes/{id} route with valid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->putJson('/api/pacientes/' . $paciente->id, $data);

        // Assert that the response status code is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response JSON contains the updated patient data
        $response->assertJson([
            'nome' => $data['nome'],
            'cpf' => $data['cpf'],
            'celular' => $data['celular'],
        ]);

        // Assert that the patient is updated in the database
        $this->assertDatabaseHas('paciente', $data);
    }

    public function testUpdatePacienteNotFound()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake data array for updating the patient
        $data = [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a PUT request to the /api/pacientes/{id} route with an invalid ID and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->putJson('/api/pacientes/999', $data);

        // Assert that the response status code is 404 (Not Found)
        $response->assertStatus(404);

        // Assert that the response JSON contains the "Paciente not found" message
        $response->assertJson([
            'message' => 'Paciente not found',
        ]);
    }

    public function testUpdatePacienteUnauthenticated()
    {
        // Create a new patient in the database
        $paciente = Paciente::factory()->create();

        // Create a fake data array for updating the patient
        $data = [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a PUT request to the /api/pacientes/{id} route without authentication
        $response = $this->putJson('/api/pacientes/' . $paciente->id, $data);

        // Assert that the response status code is 401 (Unauthorized)
        $response->assertStatus(401);

        // Assert that the response JSON contains the "Unauthorized" message
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
