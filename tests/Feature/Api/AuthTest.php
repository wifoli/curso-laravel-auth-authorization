<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validations_auth()
    {
        $response = $this->postJson('/auth', []);

        $response->assertStatus(422);
    }

    public function test_auth()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/auth', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'identify',
                'name',
                'email',
                'permissions' => [],
            ],
            'token'
        ]);

        $response->assertStatus(200);
    }

    public function test_error_password_auth()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/auth', [
            'email' => $user->email,
            'password' => 'fake_password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(422);
    }

    public function test_error_logout()
    {
        $response = $this->postJson('/logout', []);

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/logout');

        $response->assertStatus(200);
    }

    public function test_error_me()
    {
        $response = $this->getJson('/me', []);

        $response->assertStatus(401);
    }

    public function test_me()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/me');

        $response->assertJsonStructure([
            'data' => [
                'identify',
                'name',
                'email',
                'permissions' => [],
            ],
        ]);

        $response->assertStatus(200);
    }
}
