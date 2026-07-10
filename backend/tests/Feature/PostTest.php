<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Category;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->editor = User::factory()->create(['role' => 'editor']);
    $this->viewer = User::factory()->create(['role' => 'viewer']);
});

test('authenticated user can list posts', function () {
    Post::factory()->count(3)->create();

    $token = auth('api')->login($this->admin);
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/v1/posts');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [['id', 'title', 'status', 'author']],
            'meta' => ['current_page', 'total'],
        ]);
});

test('unauthenticated user cannot list posts', function () {
    $response = $this->getJson('/api/v1/posts');

    $response->assertStatus(401);
});

test('admin can create a post', function () {
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/posts', [
        'title' => 'Test Post Title',
        'content' => 'This is the content of the test post.',
        'status' => 'draft',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['data' => ['id', 'title', 'slug', 'status', 'author']]);
});

test('viewer cannot create a post', function () {
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/posts', [
        'title' => 'Test Post',
        'content' => 'Content',
        'status' => 'draft',
    ]);

    $response->assertForbidden();
});

test('admin can update a post', function () {
    $post = Post::factory()->create(['user_id' => $this->admin->id]);
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->putJson("/api/v1/posts/{$post->id}", [
        'title' => 'Updated Title',
    ]);

    $response->assertOk()
        ->assertJson(['data' => ['title' => 'Updated Title']]);
});

test('viewer cannot update a post', function () {
    $post = Post::factory()->create();
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->putJson("/api/v1/posts/{$post->id}", [
        'title' => 'Hacked Title',
    ]);

    $response->assertForbidden();
});

test('admin can delete a post', function () {
    $post = Post::factory()->create(['user_id' => $this->admin->id]);
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/posts/{$post->id}");

    $response->assertStatus(204);
    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('viewer cannot delete a post', function () {
    $post = Post::factory()->create();
    $token = auth('api')->login($this->viewer);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/posts/{$post->id}");

    $response->assertForbidden();
});

test('editor cannot delete a post', function () {
    $post = Post::factory()->create();
    $token = auth('api')->login($this->editor);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/v1/posts/{$post->id}");

    $response->assertForbidden();
});

test('post slug is auto-generated from title', function () {
    $token = auth('api')->login($this->admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/v1/posts', [
        'title' => 'My Awesome Post Title',
        'content' => 'Content here',
        'status' => 'draft',
    ]);

    $response->assertStatus(201)
        ->assertJson(['data' => ['slug' => 'my-awesome-post-title']]);
});

test('posts can be filtered by status', function () {
    Post::factory()->count(2)->published()->create(['user_id' => $this->admin->id]);
    Post::factory()->create(['user_id' => $this->admin->id, 'status' => 'draft']);

    $token = auth('api')->login($this->admin);
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson('/api/v1/posts?status=published');

    $response->assertOk();
    expect(count($response->json('data')))->toBe(2);
});
