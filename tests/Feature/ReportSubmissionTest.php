<?php

use App\Models\Report;
use App\Models\User;
use App\Notifications\NewReportSubmitted;
use Laravel\Sanctum\Sanctum;

test('administrators cannot submit reports', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->postJson('/api/reports', Report::factory()->make()->toArray())
        ->assertForbidden();
});

test('citizens can submit reports and every admin is notified', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Sanctum::actingAs(User::factory()->create(), ['*']); // a citizen
    $this->postJson('/api/reports', Report::factory()->make()->toArray())
        ->assertOk();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $admin->id,
        'type' => NewReportSubmitted::class,
    ]);
});

test('a report can be submitted without a description', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $payload = Report::factory()->make()->toArray();
    unset($payload['description']);

    $this->postJson('/api/reports', $payload)->assertOk();
});
