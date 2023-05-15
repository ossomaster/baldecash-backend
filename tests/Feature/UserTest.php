<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_users_as_administrador()
    {
        // create a user with role 'administrador'
        $administrador = User::factory()->create([
            'role' => User::ROLES['administrador']
        ]);

        // acting as 'administrador'
        Sanctum::actingAs($administrador);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_list_users_as_revisor()
    {
        // create a user with role 'revisor'
        $revisor = User::factory()->create([
            'role' => User::ROLES['revisor']
        ]);

        // acting as 'revisor'
        Sanctum::actingAs($revisor);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_list_users_is_ordered_descending_by_created_at()
    {
        // create a user with role 'administrador'
        $administrador = User::factory()->create([
            'role' => User::ROLES['administrador'],
            'created_at' => now()->subDays(1)
        ]);

        // create a user
        $user = User::factory()->create();

        // acting as 'administrador'
        Sanctum::actingAs($administrador);
        $response = $this->getJson('/api/users');

        $response->assertStatus(200);

        $users = $response->json()['users'];

        // check if users are ordered descending by created_at
        $this->assertEquals($user->id, $users[0]['id']);
    }

    public function test_store_user_as_administrador()
    {
        // create a user with role 'administrador'
        $administrador = User::factory()->create([
            'role' => User::ROLES['administrador']
        ]);

        // acting as 'administrador'
        Sanctum::actingAs($administrador);

        $formData = User::factory()->make()->toArray();
        $formData['password'] = 'password';
        $formData['password_confirmation'] = 'password';

        // check if form data is correct
        $wrongFormData = $formData;
        unset($wrongFormData['first_name']);

        $response = $this->postJson('/api/users', $wrongFormData);
        $response->assertStatus(422);

        // create a user
        $response = $this->postJson('/api/users', $formData);
        $response->assertStatus(201);

        // check if user was created
        $this->assertDatabaseHas('users', [
            'email' => $formData['email']
        ]);
    }

    public function test_update_user_as_administrador()
    {
        // create a user with role 'administrador'
        $administrador = User::factory()->create([
            'role' => User::ROLES['administrador']
        ]);

        // create a user to be updated
        $user = User::factory()->create();

        // acting as 'administrador'
        Sanctum::actingAs($administrador);

        $formData = $user->toArray();

        // check if email can't be repeated
        $wrongFormData = $formData;
        $wrongFormData['email'] = $administrador->email;

        $response = $this->putJson('/api/users/' . $user->id, $wrongFormData);
        $response->assertStatus(422);

        // update a user
        $formData['first_name'] = 'updated';
        $response = $this->putJson('/api/users/' . $user->id, $formData);
        $response->assertStatus(200);

        // check if user was updated
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => $formData['first_name']
        ]);
    }

    public function test_delete_user_as_administrador()
    {
        // create a user with role 'administrador'
        $administrador = User::factory()->create([
            'role' => User::ROLES['administrador']
        ]);

        // create a user to be deleted
        $user = User::factory()->create();

        // acting as 'administrador'
        Sanctum::actingAs($administrador);

        // check if user can delete himself
        $response = $this->deleteJson('/api/users/' . $administrador->id);
        $response->assertStatus(403);

        // delete a user
        $response = $this->deleteJson('/api/users/' . $user->id);
        $response->assertNoContent();

        // check if user was deleted
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    function test_revisor_cannot_store_user()
    {
        // create a user with role 'revisor'
        $revisor = User::factory()->create([
            'role' => User::ROLES['revisor']
        ]);

        // acting as 'revisor'
        Sanctum::actingAs($revisor);

        // try to create a user
        $response = $this->postJson('/api/users', []);
        $response->assertStatus(403);
    }

    function test_revisor_cannot_update_user()
    {
        // create a user with role 'revisor'
        $revisor = User::factory()->create([
            'role' => User::ROLES['revisor']
        ]);

        // acting as 'revisor'
        Sanctum::actingAs($revisor);

        // try to update a user
        $response = $this->putJson('/api/users/' . $revisor->id, []);
        $response->assertStatus(403);
    }

    function test_revisor_cannot_delete_user()
    {
        // create a user with role 'revisor'
        $revisor = User::factory()->create([
            'role' => User::ROLES['revisor']
        ]);

        // acting as 'revisor'
        Sanctum::actingAs($revisor);

        // try to delete a user
        $response = $this->deleteJson('/api/users/' . $revisor->id);
        $response->assertStatus(403);
    }
}
