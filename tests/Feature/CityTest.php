<?php

use App\Models\City;

test('get cities', function () {
    $response = $this->get('/api/cities');
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'total_result',
        'last_page',
        'data',
        'meta',
    ]);

    $response->assertJsonPath('total_result', 14);
});

test('show city', function () {
    $city = City::inRandomOrder()->first();

    $response = $this->get('/api/cities/' . $city->id);
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'data',
    ]);

    $response->assertJsonPath('data.id', $city->id);
    $response->assertJsonPath('data.name', $city->name);
    $response->assertJsonPath('data.code', $city->code);
    $response->assertJsonPath('data.latitude', $city->latitude);
    $response->assertJsonPath('data.longitude', $city->longitude);
    $response->assertJsonPath('data.active', $city->active);
});

test('search cities', function () {
    $response = $this->get('/api/cities?filters[name]=الحسكة');
    $response->assertOk();
    $response->assertJsonPath('total_result', 1);
    $response->assertJsonPath('data.0.name', 'الحسكة');
});