<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicoPacienteTest extends TestCase
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

    public function testGetMedicoPacientesSuccess()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a new doctor with patients in the database
        $medico = Medico::factory()->create();
        $pacientes = Paciente::factory()->count(3)->create();

        $medico->pacientes()->attach($pacientes);

        // Send a GET request to the /api/medicos/{id_medico}/pacientes route with authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->getJson('/api/medicos/' . $medico->id . '/pacientes');

        // Assert that the response status code is 200 (success)
        $response->assertStatus(200);

        // Assert that the response JSON contains the patients data
        $response->assertJsonCount(3); // Assuming there are 3 patients attached to the doctor
    }

    public function testGetMedicoPacientesNotFound()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Send a GET request to a non-existent doctor's patients route with authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->getJson('/api/medicos/999/pacientes');

        // Assert that the response status code is 404 (not found)
        $response->assertStatus(404);

        // Assert that the response JSON contains an error message indicating the doctor was not found
        $response->assertJson([
            'message' => 'Doctor not found',
        ]);
    }

    public function testGetMedicoPacientesUnauthenticated()
    {
        // Send a GET request to the /api/medicos/{id_medico}/pacientes route without authentication
        $response = $this->getJson('/api/medicos/1/pacientes');

        // Assert that the response status code is 401 (unauthorized)
        $response->assertStatus(401);

        // Assert that the response JSON contains an error message indicating unauthorized access
        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
}
