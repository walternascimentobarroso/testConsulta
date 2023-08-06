<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Cidades;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicoTest extends TestCase
{
    use RefreshDatabase;

    public function testGetMedicosSuccess()
    {
        // Create some fake doctors using the Medico model factory
        Medico::factory()->count(5)->create();

        // Send a GET request to the /medicos route
        $response = $this->get('/api/medicos');

        // Assert that the response status code is 200 (success)
        $response->assertStatus(200);

        // Assert that the response JSON contains an array of doctors
        $response->assertJsonStructure([
            '*' => [
                'id',
                'nome',
                'especialidade',
                'cidade_id',
            ],
        ]);
    }

    protected function getAuthToken()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Generate the JWT token for the user
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    public function setUp(): void
    {
        parent::setUp();

        // Seed the test database with cities
        Cidades::factory()->count(2)->create();
    }


    public function testCreateMedicoSuccess()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake data array for a new doctor
        $data = [
            'nome' => 'Dr. John Doe',
            'especialidade' => 'Cardiology',
            'cidade_id' => 1,
        ];

        // Send a POST request to the /api/medicos route with valid data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/medicos', $data);

        // Assert that the response status code is 201 (created)
        $response->assertStatus(201);

        // Assert that the response JSON contains the doctor data
        $response->assertJson([
            'nome' => 'Dr. John Doe',
            'especialidade' => 'Cardiology',
            'cidade_id' => 1,
        ]);

        // Assert that the doctor is saved in the database
        $this->assertDatabaseHas((new Medico)->getTable(), $data);
    }

    public function testCreateMedicoMissingData()
    {
        // Get the authentication token
        $token = $this->getAuthToken();

        // Create a fake data array for a new doctor with missing 'especialidade'
        $data = [
            'nome' => 'Dr. Jane Smith',
            'cidade_id' => 2,
        ];

        // Send a POST request to the /api/medicos route with missing data and authentication token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/medicos', $data);

        // Assert that the response status code is 422 (unprocessable entity)
        $response->assertStatus(422);

        // Assert that the response JSON contains an error message about the missing data
        $response->assertJsonValidationErrors('especialidade');
    }

    public function testCreateMedicoUnauthenticated()
    {
        // Create a fake data array for a new doctor
        $data = [
            'nome' => 'Dr. Jane Smith',
            'especialidade' => 'Cardiology',
            'cidade_id' => 2,
        ];

        // Send a POST request to the /api/medicos route without authentication
        $response = $this->postJson('/api/medicos', $data);

        // Assert that the response status code is 401 (unauthorized)
        $response->assertStatus(401);

        // Assert that the response JSON contains an error message indicating unauthorized access
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
