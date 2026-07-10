<?php

use App\Models\User;
use App\Models\Category;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->editor = User::factory()->create(['role' => 'editor']);
    $this->viewer = User::factory()->create(['role' => 'viewer']);
});

test('authenticated user can list categories', function () {
    Category::factory()->count(3)->create();

    $token = auth('api')->login($this->admin);
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/v1/categories');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [['id', 'name', 'slug']],
        ]);
});

test('unauthenticated user cannot list categories', function () {
    $response = $this->getJson('/api/v1/categories');

    $response->assertStatus(401);
});

test('admin can create a category', function () {
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/categories', [
        'name' => 'Technology',
        'description' => 'Tech-related posts',
    ]);

    $response->assertStatus(201)
        ->assertJson(['data' => ['name' => 'Technology', 'slug' => 'technology']]);
});

test('viewer cannot create a category', function () {
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/categories', [
        'name' => 'Hacked Category',
    ]);

    $response->assertForbidden();
});

test('editor can create a category', function () {
    $token = auth('api')->login($this->editor);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/categories', [
        'name' => 'Tutorials',
    ]);

    $response->assertStatus(201);
});

test('admin can update a category', function () {
    $category = Category::factory()->create();
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->putJson("/api/v1/categories/{$category->id}", [
        'name' => 'Updated Name',
    ]);

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'Updated Name']]);
});

test('viewer cannot update a category', function () {
    $category = Category::factory()->create();
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->putJson("/api/v1/categories/{$category->id}", [
        'name' => 'Hacked',
    ]);

    $response->assertForbidden();
});

test('admin can delete a category', function () {
    $category = Category::factory()->create();
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/categories/{$category->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('viewer cannot delete a category', function () {
    $category = Category::factory()->create();
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/categories/{$category->id}");

    $response->assertForbidden();
});

test('editor cannot delete a category', function () {
    $category = Category::factory()->create();
    $token = auth('api')->login($this->editor);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/categories/{$category->id}");

    $response->assertForbidden();
});

test('category slug is auto-generated from name', function () {
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/categories', [
        'name' => 'Web Development',
    ]);

    $response->assertStatus(201)
        ->assertJson(['data' => ['slug' => 'web-development']]);
});
