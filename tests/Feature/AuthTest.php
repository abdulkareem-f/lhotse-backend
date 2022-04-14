<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register(){
        $user = [
            'name'      =>  $this->faker->name(),
            'email'     =>  $this->faker->unique()->safeEmail(),
            'password'  =>  bcrypt('test_password')
        ];

        $this->json('POST', 'api/register', $user, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => ['token', 'user' => ['id', 'name', 'email']],
                    'msg'
                ]
            );
    }

    public function test_login(){
        $user = User::create([
            'name'      =>  $this->faker->name(),
            'email'     =>  $this->faker->unique()->safeEmail(),
            'password'  =>  'test_password'
        ]);

        $userData = ['email' => $user->email, 'password' => 'test_password'];

        $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => ['token', 'user' => ['id', 'name', 'email']],
                    'msg'
                ]
            );
    }

    public function test_logout(){
        $this->actingAs($this->user, 'sanctum');
        $this->json('POST', 'api/logout')
            ->assertStatus(200)
            ->assertExactJson(['msg' => 'Logged out successfully']);
    }
}
