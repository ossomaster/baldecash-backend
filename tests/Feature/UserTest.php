<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_users()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_list_users_as_revisor()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }

    public function test_store_user()
    {
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

    public function test_update_user()
    {
        // create a user to be updated
        $user = User::factory()->create();

        // create another user to be used as email
        $anotherUser = User::factory()->create();

        $formData = $user->toArray();

        // check if email can't be repeated
        $wrongFormData = $formData;
        $wrongFormData['email'] = $anotherUser->email;

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

    public function test_delete_user()
    {
        // create a user to be deleted
        $user = User::factory()->create();

        // delete a user
        $response = $this->deleteJson('/api/users/' . $user->id);
        $response->assertNoContent();

        // check if user was deleted
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

}
