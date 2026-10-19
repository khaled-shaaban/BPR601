<?php

use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);
});

test('admin can view dashboard stats', function () {
    Report::factory()->count(5)->create(['status' => 'pending']);
    Report::factory()->count(3)->create(['status' => 'completed']);

    $this->getJson('/api/stats')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'totals' => ['reports', 'users', 'authorities', 'open_reports'],
                'reports_by_status',
                'reports_by_type',
                'reports_by_priority',
                'reports_by_city',
                'recent_reports',
            ],
        ])
        ->assertJsonPath('data.totals.reports', 8)
        ->assertJsonPath('data.totals.open_reports', 5)
        ->assertJsonPath('data.reports_by_status.pending', 5)
        ->assertJsonPath('data.reports_by_status.completed', 3);
});

test('stats backfill every status with zero', function () {
    $this->getJson('/api/stats')
        ->assertOk()
        ->assertJsonPath('data.reports_by_status.cancelled', 0)
        ->assertJsonPath('data.totals.reports', 0);
});

test('recent reports are limited to five', function () {
    Report::factory()->count(8)->create();

    $this->getJson('/api/stats')
        ->assertOk()
        ->assertJsonCount(5, 'data.recent_reports');
});

test('regular users cannot view stats', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->getJson('/api/stats')->assertForbidden();
});
