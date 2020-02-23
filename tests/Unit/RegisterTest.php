<?php

namespace Tests\Unit;

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
}
