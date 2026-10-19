<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('register creates a user, assigns the user role, and returns a token', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Khaled',
        'phone' => '0911111111',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'data' => ['user' => ['id', 'name', 'phone'], 'token'],
    ]);

    $this->assertDatabaseHas('users', ['phone' => '0911111111']);

    $user = User::where('phone', '0911111111')->first();
    expect($user->hasRole('user'))->toBeTrue();
});

test('register requires a matching password confirmation', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Khaled',
        'phone' => '0911111112',
        'password' => 'password',
        'password_confirmation' => 'different',
    ]);

    $response->assertStatus(422);
});

test('register rejects a duplicate phone', function () {
    User::factory()->create(['phone' => '0911111113']);

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Khaled',
        'phone' => '0911111113',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422);
});

test('login returns a token for valid credentials', function () {
    User::factory()->create([
        'phone' => '0922222222',
        'password' => 'password',
        'active' => true,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'phone' => '0922222222',
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'data' => ['user', 'token'],
    ]);
});

test('login rejects invalid credentials', function () {
    User::factory()->create([
        'phone' => '0922222223',
        'password' => 'password',
        'active' => true,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'phone' => '0922222223',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});

test('login rejects disabled accounts', function () {
    User::factory()->create([
        'phone' => '0922222224',
        'password' => 'password',
        'active' => false,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'phone' => '0922222224',
        'password' => 'password',
    ]);

    $response->assertStatus(403);
});

test('me returns the authenticated user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson('/api/auth/me');
    $response->assertOk();
    $response->assertJsonPath('data.user.id', $user->id);
});

test('logout succeeds for an authenticated user', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $response = $this->postJson('/api/auth/logout');
    $response->assertOk();
});

test('protected report routes reject guests', function () {
    $this->getJson('/api/reports')->assertUnauthorized();
});

test('authority write routes reject guests', function () {
    $this->postJson('/api/authorities', [])->assertUnauthorized();
});

test('public reference routes are accessible to guests', function () {
    $this->getJson('/api/cities')->assertOk();
    $this->getJson('/api/areas')->assertOk();
    $this->getJson('/api/authorities')->assertOk();
});
