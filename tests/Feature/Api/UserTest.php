<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_users_unauthenticated()
    {
        $response = $this->getJson('/users');

        $response->assertStatus(401);
    }

    public function test_get_users_unauthorized()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/users');

        $response->assertStatus(403);
    }

    public function test_get_users()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/users');

        $response->assertStatus(200);
    }

    public function test_count_users()
    {
        $numberOfUsers = 10;

        // Cria vários usuários com token e seleciona o primeiro
        User::factory()->count($numberOfUsers)->create();
        $user = User::first();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/users');

        $response->assertJsonCount($numberOfUsers, 'data');
        $response->assertStatus(200);
    }

    public function test_get_fail_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/users/fake_value");


        $response->assertStatus(404);
    }

    public function test_get_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/users/{$user->uuid}");


        $response->assertStatus(200);
    }

    public function test_validations_store_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/users', []);


        $response->assertStatus(422);
    }

    public function test_store_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/users', [
                'name' => 'test',
                'email' => 'teste@email.com',
                'password' => '123456',
            ]);

        $response->assertStatus(201);
    }

    public function test_validations_404_update_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/users/fake_value', [
                'name' => 'test',
                'email' => 'teste@email.com',
            ]);

        $response->assertStatus(404);
    }

    public function test_validations_update_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/users/{$user->uuid}", []);

        $response->assertStatus(422);
    }

    public function test_update_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/users/{$user->uuid}", [
                'name' => 'test',
                'email' => 'teste@email.com',
            ]);

        $response->assertStatus(200);
    }

    public function test_validations_404_delete_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson('/users/fake_value', []);

        $response->assertStatus(404);
    }

    public function test_delete_user()
    {
        // Cria um usuário com token
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Cria uma permissao e adiciona ao usuário
        $permission = Permission::factory()->create([
            'name' => 'users',
        ]);
        $user->permissions()->attach($permission);

        $response = $this
            ->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/users/{$user->uuid}", []);

        $response->assertStatus(204);
    }
}
