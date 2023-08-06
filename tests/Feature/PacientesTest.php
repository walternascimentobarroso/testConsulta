<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PacientesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreatePacienteSuccess()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake data array for a new patient
        $data = [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a POST request to the /api/pacientes route with valid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/pacientes', $data);

        // Assert that the response status code is 201 (created)
        $response->assertStatus(201);

        // Assert that the response JSON contains the patient data
        $response->assertJson([
            'nome' => $data['nome'],
            'cpf' => $data['cpf'],
            'celular' => $data['celular'],
        ]);

        // Assert that the patient is saved in the database
        $this->assertDatabaseHas('paciente', $data);
    }

    public function testCreatePacienteInvalidData()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake data array for a new patient with missing 'nome'
        $data = [
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a POST request to the /api/pacientes route with missing data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/pacientes', $data);

        // Assert that the response status code is 422 (unprocessable entity)
        $response->assertStatus(422);

        // Assert that the response JSON contains an error message about the missing 'nome' field
        $response->assertJsonValidationErrors('nome');
    }

    public function testCreatePacienteUnauthenticated()
    {
        // Create a fake data array for a new patient
        $data = [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->cpf,
            'celular' => $this->faker->cellphoneNumber,
        ];

        // Send a POST request to the /api/pacientes route without authentication token
        $response = $this->postJson('/api/pacientes', $data);

        // Assert that the response status code is 401 (unauthorized)
        $response->assertStatus(401);

        // Assert that the response JSON contains an error message indicating unauthorized access
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);

        // Assert that the patient is not saved in the database
        $this->assertDatabaseMissing('paciente', $data);
    }

    protected function getAuthToken()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Generate the JWT token for the user
        $token = JWTAuth::fromUser($user);

        return $token;
    }
}
