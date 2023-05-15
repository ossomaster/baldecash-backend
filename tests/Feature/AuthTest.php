<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_authenticate()
    {
        // create a user
        $user = User::factory()->create();

        // authenticate
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['access_token', 'token_type', 'user']);
    }

    public function test_user_cannot_authenticate_with_incorrect_password()
    {
        // create a user
        $user = User::factory()->create();

        // authenticate
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_cannot_authenticate_with_deleted_account()
    {
        // create a user
        $user = User::factory()->create();
        $user->delete();

        // authenticate
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_cannot_request_with_deleted_account()
    {
        // create a user
        $user = User::factory()->create();

        // authenticate
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();

        // delete user
        $user->delete();

        // request
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }
}
