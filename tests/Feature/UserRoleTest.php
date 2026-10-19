<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('a super admin can change another users role', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);

    $user = User::factory()->create();
    $user->assignRole('user');

    $this->patchJson("/api/users/{$user->id}/role", ['role' => 'admin'])
        ->assertOk();

    expect($user->fresh()->hasRole('admin'))->toBeTrue();
    expect($user->fresh()->hasRole('user'))->toBeFalse();
});

test('regular admins cannot change roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $user = User::factory()->create();

    $this->patchJson("/api/users/{$user->id}/role", ['role' => 'admin'])
        ->assertForbidden();
});

test('a super admin cannot change their own role', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);

    $this->patchJson("/api/users/{$super->id}/role", ['role' => 'user'])
        ->assertStatus(422);

    expect($super->fresh()->hasRole('super_admin'))->toBeTrue();
});

test('the role must be a valid role', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);

    $user = User::factory()->create();

    $this->patchJson("/api/users/{$user->id}/role", ['role' => 'wizard'])
        ->assertStatus(422);
});

test('a super admin can make a user an authority representative', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);

    $authority = App\Models\Authority::factory()->create();
    $user = User::factory()->create();

    $this->patchJson("/api/users/{$user->id}/role", [
        'role' => 'representative',
        'authority_id' => $authority->id,
    ])->assertOk();

    $fresh = $user->fresh();
    expect($fresh->hasRole('representative'))->toBeTrue();
    expect($fresh->authority_id)->toBe($authority->id);
});

test('making a representative requires an authority', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);
    $user = User::factory()->create();

    $this->patchJson("/api/users/{$user->id}/role", ['role' => 'representative'])
        ->assertStatus(422);
});

test('changing away from representative clears the authority', function () {
    $super = User::factory()->create();
    $super->assignRole('super_admin');
    Sanctum::actingAs($super, ['*']);

    $authority = App\Models\Authority::factory()->create();
    $user = User::factory()->create(['authority_id' => $authority->id]);
    $user->assignRole('representative');

    $this->patchJson("/api/users/{$user->id}/role", ['role' => 'user'])->assertOk();

    expect($user->fresh()->authority_id)->toBeNull();
});
