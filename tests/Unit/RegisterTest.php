<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    public function test_user_registers() {

        $data = [
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'password' => bcrypt('12345'),
        ];

        $response = [
            'message' => 'User created',
            'data' => null
        ];
        // create

        $this->post('/api/register', $data)
            ->assertStatus(201)
            ->assertJson($response);
    }

    public function test_invalid_credentials_registration() {

        $this->json('POST', '/api/register')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'error'
            ]);
    }

    public function test_post_already_registered_user() {

        $email = $this->faker->email;
        $name = $this->faker->name;
        $applicant = factory(User::class)->create([
            'email' => $email,
            'name' => $name,
            'password' => bcrypt('12345'),
        ]);

        $data = [
            'email' => $email,
            'name' => $name,
            'password' => bcrypt('12345')
        ];

        $response = [
            "message",
            "error"
        ];

        $this->json('POST','/api/register', $data)
            ->assertStatus(422)
            ->assertJsonStructure($response);
    }
}
