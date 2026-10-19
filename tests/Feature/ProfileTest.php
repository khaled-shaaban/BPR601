<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

test('a user can update their profile', function () {
    $user = User::factory()->create(['name' => 'Old Name', 'phone' => '0911000000']);
    Sanctum::actingAs($user, ['*']);

    $this->putJson('/api/auth/profile', ['name' => 'New Name', 'phone' => '0911000001'])
        ->assertOk()
        ->assertJsonPath('data.user.name', 'New Name')
        ->assertJsonPath('data.user.phone', '0911000001');

    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name', 'phone' => '0911000001']);
});

test('a user may keep their own phone when updating', function () {
    $user = User::factory()->create(['phone' => '0911000010']);
    Sanctum::actingAs($user, ['*']);

    $this->putJson('/api/auth/profile', ['name' => 'Same Phone', 'phone' => '0911000010'])->assertOk();
});

test('profile phone must not collide with another user', function () {
    User::factory()->create(['phone' => '0911000099']);
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->putJson('/api/auth/profile', ['name' => 'X', 'phone' => '0911000099'])->assertStatus(422);
});

test('a user can change their password', function () {
    $user = User::factory()->create(['password' => 'old-password']);
    Sanctum::actingAs($user, ['*']);

    $this->putJson('/api/auth/password', [
        'current_password' => 'old-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertOk();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('changing password requires the correct current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);
    Sanctum::actingAs($user, ['*']);

    $this->putJson('/api/auth/password', [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertStatus(422);
});
