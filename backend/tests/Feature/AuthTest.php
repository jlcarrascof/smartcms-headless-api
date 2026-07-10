<?php

use App\Models\User;
use App\Services\AuthService;

beforeEach(function () {
    $this->authService = app(AuthService::class);
    $this->userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];
});

test('user can register', function () {
    $response = $this->postJson('/api/v1/auth/register', $this->userData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in', 'user',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'editor',
    ]);
});

test('user cannot register with existing email', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->postJson('/api/v1/auth/register', $this->userData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('user can login with valid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in', 'user',
        ]);
});

test('user cannot login with invalid credentials', function () {
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Invalid credentials']);
});

test('authenticated user can get their profile', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/v1/auth/me');

    $response->assertOk()
        ->assertJson(['id' => $user->id, 'email' => $user->email]);
});

test('unauthenticated user cannot access profile', function () {
    $response = $this->getJson('/api/v1/auth/me');

    $response->assertStatus(401);
});

test('user can refresh token', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/auth/refresh');

    $response->assertOk()
        ->assertJsonStructure(['token']);
});

test('user can logout', function () {
    $user = User::factory()->create();
    $token = auth('api')->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/auth/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Session closed successfully']);
});
