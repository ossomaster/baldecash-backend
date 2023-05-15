<?php

namespace Tests\Feature;

use App\Mail\AccountCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailAccountCreatedTest extends TestCase
{
    public function test_email_account_created_content()
    {
        $user = User::factory()->make();

        $mailable = new AccountCreated($user, 'password');

        $mailable->assertSeeInHtml($user->first_name);
        $mailable->assertSeeInHtml('Cuenta creada');
    }
}
