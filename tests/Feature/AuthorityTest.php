<?php

use App\Models\Authority;
use App\Models\Area;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    // Reference-data management is restricted to super admins.
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    Sanctum::actingAs($admin, ['*']);
});

test('get authorities', function () {

    Authority::factory()->count(10)->create();

    $response = $this->get('/api/authorities');
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'total_result',
        'last_page',
        'data',
        'meta',
    ]);

    $response->assertJsonPath('total_result', 10);
});

test('show authority', function () {
    $authority = Authority::factory()->create();

    $response = $this->get('/api/authorities/' . $authority->id);
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'data',
    ]);

    $response->assertJsonPath('data.id', $authority->id);
    $response->assertJsonPath('data.name', $authority->name);
    $response->assertJsonPath('data.code', $authority->code);
    $response->assertJsonPath('data.description', $authority->description);
    $response->assertJsonPath('data.icon', $authority->icon);
    $response->assertJsonPath('data.color', $authority->color);
    $response->assertJsonPath('data.area_id', $authority->area_id);
    $response->assertJsonPath('data.address', $authority->address);
    $response->assertJsonPath('data.contact_phone_1', $authority->contact_phone_1);
    $response->assertJsonPath('data.contact_phone_2', $authority->contact_phone_2);
    $response->assertJsonPath('data.active', $authority->active);
});

test('search authorities', function () {

    Authority::factory()->count(10)->create();

    $authority = Authority::inRandomOrder()->first();

    $response = $this->get('/api/authorities?filters[name]=' . $authority->name);
    $response->assertOk();
    $response->assertJsonPath('data.0.id', $authority->id);
    $response->assertJsonPath('data.0.name', $authority->name);
    $response->assertJsonPath('data.0.code', $authority->code);
    $response->assertJsonPath('data.0.description', $authority->description);
    $response->assertJsonPath('data.0.icon', $authority->icon);
    $response->assertJsonPath('data.0.color', $authority->color);
    $response->assertJsonPath('data.0.area_id', $authority->area_id);
    $response->assertJsonPath('data.0.address', $authority->address);
    $response->assertJsonPath('data.0.contact_phone_1', $authority->contact_phone_1);
    $response->assertJsonPath('data.0.contact_phone_2', $authority->contact_phone_2);
    $response->assertJsonPath('data.0.active', $authority->active);
});

test('store authority', function () {
    $authority = Authority::factory()->make();
    $response = $this->post('/api/authorities', $authority->toArray());
    $response->assertOk();

    $showResponse = $this->get('/api/authorities/' . $response->json('data.id'));
    $showResponse->assertOk();
    $showResponse->assertJsonPath('data.id', $response->json('data.id'));
    $showResponse->assertJsonPath('data.name', $authority->name);
    $showResponse->assertJsonPath('data.code', $authority->code);
    $showResponse->assertJsonPath('data.description', $authority->description);
    $showResponse->assertJsonPath('data.icon', $authority->icon);
    $showResponse->assertJsonPath('data.color', $authority->color);
    $showResponse->assertJsonPath('data.area_id', $authority->area_id);
    $showResponse->assertJsonPath('data.address', $authority->address);
    $showResponse->assertJsonPath('data.contact_phone_1', $authority->contact_phone_1);
    $showResponse->assertJsonPath('data.contact_phone_2', $authority->contact_phone_2);
    $showResponse->assertJsonPath('data.active', $authority->active);
});

test('update authority', function () {
    $authority = Authority::factory()->create();

    $area = Area::latest()->first();

    $newAuthority = [
        'name' => fake()->name(),
        'code' => fake()->unique()->word(),
        'description' => fake()->sentence(),
        'icon' => fake()->imageUrl(),
        'color' => fake()->colorName(),
        'area_id' => $area->id,
        'address' => fake()->address(),
        'contact_phone_1' => fake()->phoneNumber(),
        'contact_phone_2' => fake()->phoneNumber(),
        'active' => fake()->boolean(),
    ];

    $response = $this->put('/api/authorities/' . $authority->id, $newAuthority);
    $response->assertOk();

    $showResponse = $this->get('/api/authorities/' . $authority->id);
    $showResponse->assertOk();

    $showResponse->assertJsonPath('data.id', $authority->id);
    $showResponse->assertJsonPath('data.name', $newAuthority['name']);
    $showResponse->assertJsonPath('data.code', $newAuthority['code']);
    $showResponse->assertJsonPath('data.description', $newAuthority['description']);
    $showResponse->assertJsonPath('data.icon', $newAuthority['icon']);
    $showResponse->assertJsonPath('data.color', $newAuthority['color']);
    $showResponse->assertJsonPath('data.area_id', $newAuthority['area_id']);
    $showResponse->assertJsonPath('data.address', $newAuthority['address']);
    $showResponse->assertJsonPath('data.contact_phone_1', $newAuthority['contact_phone_1']);
    $showResponse->assertJsonPath('data.contact_phone_2', $newAuthority['contact_phone_2']);
    $showResponse->assertJsonPath('data.active', $newAuthority['active']);

});

test('delete authority', function () {
    $authority = Authority::factory()->create();

    $response = $this->delete('/api/authorities/' . $authority->id);
    $response->assertOk();

    $showResponse = $this->get('/api/authorities/' . $authority->id);
    $showResponse->assertNotFound();
});

test('regular users cannot create authorities', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $authority = Authority::factory()->make();

    $this->postJson('/api/authorities', $authority->toArray())->assertForbidden();
});

test('regular users cannot delete authorities', function () {
    $authority = Authority::factory()->create();
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->deleteJson('/api/authorities/' . $authority->id)->assertForbidden();
    $this->assertDatabaseHas('authorities', ['id' => $authority->id]);
});
