<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /** @test  */
    public function test_register_and_login(): void
    {
        $email = fake()->email;
        $password = fake()->password;
        $userData = [
            'name' => fake()->name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->post(route('register'), $userData)
            ->assertStatus(201)
            ->assertJsonFragment(['success'=>true]);


        $userData = [
            'email' => $email,
            'password' => $password,
        ];

        $this->post(route('login'), $userData)
            ->assertStatus(201)
            ->assertJsonFragment(['success'=>true]);
    }


}
