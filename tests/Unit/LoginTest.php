<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyClass {

}
class LoginTest extends TestCase
{
    public function test_requires_email_password() {
        $response = new \stdClass();
        $response->message = 'Parameter validation failure';

        $errors = new MyClass();
        $errors->email = ['The email field is required.'];
        $errors->password = ['The password field is required.'];

        $response->error = $errors;

        $this->json('POST', '/api/login')
            ->assertStatus(422);
    }

    public function test_user_logs_in_successfully() {
        $email = $this->faker->email;
        $applicant = factory(User::class)->create([
            'email' => $email,
            'name' => $this->faker->name,
            'password' => bcrypt('12345'),
        ]);
        $payload = ['email' => $email, 'password' => '12345'];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token'
            ]);
    }

    public function test_logs_in_invalid_credentials() {

        $payload = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => '12345'
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(401)
            ->assertJsonStructure(
                [
                    'message',
                    'error'
                ]
            );
    }
}
