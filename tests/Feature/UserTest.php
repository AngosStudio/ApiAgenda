<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_users_list(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/users')
        ;

        $response->assertStatus(200);
    }

    public function test_users_get(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/users/' . $user->id)
        ;

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $user->email)
        ;
    }

    public function test_users_get_user_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/users/1098098098')
        ;

        $response->assertStatus(404);
    }

    public function test_users_creation(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();

        $totalUsers = User::count();

        $userName = "User Test $totalUsers";
        $userEmail = "test-$totalUsers@test.com";

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post('/users', [
                "name" => $userName,
                "email" => $userEmail,
                "password" => "12345678"
            ])
        ;

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', $userName)
            ->assertJsonPath('data.email', $userEmail)
        ;
    }

    public function test_users_update(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();

        $totalUsers = User::count();

        $userName = "User Test $totalUsers";
        $userEmail = "test-$totalUsers@test.com";
        $newUser = User::create([
            "name" => $userName,
            "email" => $userEmail,
            "password" => "12345678"
        ]);

        $userName .= ' (updated)';
        $userEmail = "test-$totalUsers-ed@test.com";

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->patch('/users/'.$newUser->id, [
                "name" => $userName,
                "email" => $userEmail
            ])
        ;

        $response
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', $userName)
            ->assertJsonPath('data.email', $userEmail)
        ;
    }

    public function test_users_update_user_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->patch('/users/12189729', [
                "name" => 'teste'
            ])
        ;

        $response->assertStatus(404);
    }

    public function test_users_delete(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $userToDelete = User::where('email', 'like', 'test-%@test.com')->orderBy('id', 'desc')->first();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete('/users/'.$userToDelete->id)
        ;

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
        ;
    }

    public function test_users_delete_user_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete('/users/12189729')
        ;

        $response->assertStatus(404);
    }
}
