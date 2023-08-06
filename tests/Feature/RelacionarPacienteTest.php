<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Cidades;
use App\Models\Paciente;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelacionarPacienteTest extends TestCase
{
    use RefreshDatabase;



    protected function getAuthToken()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Generate the JWT token for the user
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    public function testRelacionarPacienteSuccess()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake doctor and patient data
        $medico = Medico::factory()->create();
        $paciente = Paciente::factory()->create();

        // Send a POST request to the /api/medicos/{id_medico}/pacientes route with valid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson("/api/medicos/{$medico->id}/pacientes", [
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
        ]);

        // Assert that the response status code is 200 (success)
        $response->assertStatus(200);

        // Assert that the response JSON contains the doctor and patient data
        $response->assertJson([
            'medico' => [
                'id' => $medico->id,
                'nome' => $medico->nome,
                'especialidade' => $medico->especialidade,
                'cidade_id' => $medico->cidade_id,
            ],
            'paciente' => [
                'id' => $paciente->id,
                'nome' => $paciente->nome,
                'cpf' => $paciente->cpf,
                'celular' => $paciente->celular,
            ],
        ]);

        // Assert that the relationship between the doctor and patient exists in the database
        $this->assertDatabaseHas('medico_paciente', [
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
        ]);
    }

    public function testRelacionarPacienteInvalidPacienteId()
    {
        $token = $this->getAuthToken();

        $medico = Medico::factory()->create();

        $data = [
            'paciente_id' => 12345, // Invalid patient ID
        ];

        // Send a POST request to the /api/medicos/{id_medico}/pacientes route with invalid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/medicos/' . $medico->id . '/pacientes', $data);

        // Assert that the response status code is 422 (unprocessable entity)
        $response->assertStatus(422);

        // Assert that the response JSON contains the "The selected paciente id is invalid." message
        $response->assertJsonValidationErrors(['paciente_id']);
    }


    public function testRelacionarPacienteDoctorNotFound()
    {
        $token = $this->getAuthToken();
        $paciente = Paciente::factory()->create();
        $data = [
            'paciente_id' => $paciente->id, // Valid patient ID
        ];

        // Send a POST request to the /api/medicos/{id_medico}/pacientes route with invalid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/medicos/99999/pacientes', $data);

        // Assert that the response status code is 404 (not found)
        $response->assertStatus(404);

        // Assert that the response JSON contains the "Doctor not found" message
        $response->assertJson(['message' => 'Doctor not found']);
    }

    public function testRelacionarPacienteUnauthenticated()
    {
        // Send a POST request to the /api/medicos/{id_medico}/pacientes route without authentication
        $response = $this->postJson("/api/medicos/1/pacientes", [
            'medico_id' => 1,
            'paciente_id' => 1,
        ]);

        // Assert that the response status code is 401 (unauthorized)
        $response->assertStatus(401);

        // Assert that the response JSON contains the "Unauthorized" error message
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
