<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_login_form()
    {
        $response = $this->get('/login');
        $response->assertSeeText('Email');
        $response->assertStatus(200);
    }

    public function test_login_user()
    {
        $user = new User();
        $user->name = 'Alice';
        $user->email = 'alice@alice.se';
        $user->password = Hash::make('123');
        $user->save();

        $response = $this
            ->followingRedirects()
            ->post('login', [
                'email' => 'alice@alice.se',
                'password' => '123',
            ]);

        $response->assertOk();
    }


    public function test_login_user_without_password()
    {
        $user = User::factory()->create();

        $response = $this->followingRedirects($user)->post('login', [
            'email' => $user->email,
        ]);

        $response->assertSeeText('The provided credentials do not match our records!');
    }
}
