<?php

use App\Models\Area;

test('get areas', function () {

    $areas = Area::count();

    $response = $this->get('/api/areas');
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'total_result',
        'last_page',
        'data',
        'meta',
    ]);

    $response->assertJsonPath('total_result', $areas);
});

test('show area', function () {
    $area = Area::inRandomOrder()->first();

    $response = $this->get('/api/areas/' . $area->id);
    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'data',
    ]);

    $response->assertJsonPath('data.id', $area->id);
    $response->assertJsonPath('data.name', $area->name);
    $response->assertJsonPath('data.code', $area->code);
    $response->assertJsonPath('data.latitude', $area->latitude);
    $response->assertJsonPath('data.longitude', $area->longitude);
    $response->assertJsonPath('data.active', $area->active);
    $response->assertJsonPath('data.city_id', $area->city_id);
});

test('search areas', function () {
    $areas = Area::where('name', 'like', '%المشروع%')->count();

    $response = $this->get('/api/areas?filters[name]=المشروع');
    $response->assertOk();
    $response->assertJsonPath('total_result', $areas);
});