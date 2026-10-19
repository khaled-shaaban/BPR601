<?php

use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    test()->admin = $admin;
    Sanctum::actingAs($admin, ['*']);
});

test('admin can move a pending report to in_progress', function () {
    $report = Report::factory()->create(['status' => 'pending']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])
        ->assertOk()
        ->assertJsonPath('data.status', 'in_progress');
});

test('admin can cancel a pending report', function () {
    $report = Report::factory()->create(['status' => 'pending']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'cancelled'])
        ->assertOk()
        ->assertJsonPath('data.status', 'cancelled');
});

test('invalid status transition is rejected', function () {
    $report = Report::factory()->create(['status' => 'completed']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])
        ->assertStatus(422);

    expect($report->fresh()->status)->toBe('completed');
});

test('regular users cannot change report status', function () {
    $report = Report::factory()->create(['status' => 'pending']);
    Sanctum::actingAs(User::factory()->create(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])
        ->assertForbidden();
});

test('admin can assign a report to another admin', function () {
    $handler = User::factory()->create();
    $handler->assignRole('admin');
    $report = Report::factory()->create();

    $this->patchJson("/api/reports/{$report->id}/assign", ['assigned_to' => $handler->id])
        ->assertOk()
        ->assertJsonPath('data.assigned_to', $handler->id);
});

test('reports cannot be assigned to non-admins', function () {
    $user = User::factory()->create();
    $report = Report::factory()->create();

    $this->patchJson("/api/reports/{$report->id}/assign", ['assigned_to' => $user->id])
        ->assertStatus(422);

    expect($report->fresh()->assigned_to)->toBeNull();
});

test('reports created via the API always start as pending', function () {
    Sanctum::actingAs(User::factory()->create(), ['*']);
    $payload = Report::factory()->make(['status' => 'completed'])->toArray();

    $this->postJson('/api/reports', $payload)
        ->assertOk()
        ->assertJsonPath('data.status', 'pending');
});
