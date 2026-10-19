<?php

use App\Models\Authority;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function usersSuperAdmin(): User
{
    $u = User::factory()->create();
    $u->assignRole('super_admin');

    return $u;
}

test('super admin can create a staff user', function () {
    Sanctum::actingAs(usersSuperAdmin(), ['*']);

    $this->postJson('/api/users', [
        'name' => 'Admin Two',
        'phone' => '0955555555',
        'password' => 'password',
        'role' => 'admin',
    ])->assertOk()->assertJsonPath('data.name', 'Admin Two');

    $user = User::where('phone', '0955555555')->first();
    expect($user->hasRole('admin'))->toBeTrue();
});

test('super admin can create a representative tied to an authority', function () {
    $authority = Authority::factory()->create();
    Sanctum::actingAs(usersSuperAdmin(), ['*']);

    $this->postJson('/api/users', [
        'name' => 'Rep One',
        'phone' => '0966666666',
        'password' => 'password',
        'role' => 'representative',
        'authority_id' => $authority->id,
    ])->assertOk();

    $user = User::where('phone', '0966666666')->first();
    expect($user->hasRole('representative'))->toBeTrue();
    expect($user->authority_id)->toBe($authority->id);
});

test('creating a representative without an authority fails validation', function () {
    Sanctum::actingAs(usersSuperAdmin(), ['*']);

    $this->postJson('/api/users', [
        'name' => 'Rep Two',
        'phone' => '0977777777',
        'password' => 'password',
        'role' => 'representative',
    ])->assertStatus(422);
});

test('a non super admin cannot create users', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->postJson('/api/users', [
        'name' => 'X',
        'phone' => '0988888888',
        'password' => 'password',
        'role' => 'user',
    ])->assertForbidden();
});
