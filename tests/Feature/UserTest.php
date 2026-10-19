<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('super admin can list users with their roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin, ['*']);

    User::factory()->count(3)->create();

    $response = $this->getJson('/api/users');
    $response->assertOk();
    $response->assertJsonStructure([
        'total_result',
        'data' => [['id', 'name', 'phone', 'roles']],
    ]);
});

test('regular users cannot list users', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->getJson('/api/users')->assertForbidden();
});

test('regular admins cannot list users but can fetch assignees', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->getJson('/api/users')->assertForbidden();
    $this->getJson('/api/users/assignees')->assertOk();
});

test('guests cannot list users', function () {
    $this->getJson('/api/users')->assertUnauthorized();
});
