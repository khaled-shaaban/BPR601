<?php

use App\Models\Area;
use App\Models\City;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function asManager(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin, ['*']);

    return $admin;
}

test('admin can create, update and delete a city', function () {
    asManager();

    $create = $this->postJson('/api/cities', ['name' => 'مدينة اختبار', 'code' => 'TST', 'active' => true]);
    $create->assertOk()->assertJsonPath('data.code', 'TST');
    $id = $create->json('data.id');

    $this->putJson("/api/cities/{$id}", ['name' => 'مدينة محدّثة'])
        ->assertOk()
        ->assertJsonPath('data.name', 'مدينة محدّثة');

    $this->deleteJson("/api/cities/{$id}")->assertOk();
    $this->assertDatabaseMissing('cities', ['id' => $id]);
});

test('non-admins cannot manage cities', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->postJson('/api/cities', ['name' => 'X', 'code' => 'ZZZ'])->assertForbidden();
});

test('city code must be unique', function () {
    asManager();
    $existing = City::first();

    $this->postJson('/api/cities', ['name' => 'مكرر', 'code' => $existing->code])->assertStatus(422);
});

test('admin can create and delete an area', function () {
    asManager();
    $city = City::first();

    $create = $this->postJson('/api/areas', [
        'name' => 'منطقة اختبار',
        'code' => 'TST_AR',
        'city_id' => $city->id,
    ]);
    $create->assertOk()->assertJsonPath('data.code', 'TST_AR');

    $this->deleteJson('/api/areas/' . $create->json('data.id'))->assertOk();
});

test('non-admins cannot manage areas', function () {
    $city = City::first();
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->postJson('/api/areas', ['name' => 'X', 'code' => 'ZZ_AR', 'city_id' => $city->id])
        ->assertForbidden();
});
