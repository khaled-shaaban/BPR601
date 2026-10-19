<?php

use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportAssigned;
use App\Notifications\ReportStatusChanged;
use Laravel\Sanctum\Sanctum;

function makeAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('owner is notified when their report status changes', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(makeAdmin(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $owner->id,
        'notifiable_type' => User::class,
        'type' => ReportStatusChanged::class,
    ]);
});

test('assignee is notified when a report is assigned to them', function () {
    $report = Report::factory()->create();
    $handler = makeAdmin();
    Sanctum::actingAs(makeAdmin(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/assign", ['assigned_to' => $handler->id])->assertOk();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $handler->id,
        'type' => ReportAssigned::class,
    ]);
});

test('user can list their notifications with an unread count', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(makeAdmin(), ['*']);
    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    Sanctum::actingAs($owner, ['*']);
    $this->getJson('/api/notifications')
        ->assertOk()
        ->assertJsonPath('total_result', 1)
        ->assertJsonPath('meta.unread_count', 1);
});

test('the unread-count endpoint returns the user unread total', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(makeAdmin(), ['*']);
    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    Sanctum::actingAs($owner, ['*']);
    $this->getJson('/api/notifications/unread-count')
        ->assertOk()
        ->assertJsonPath('data.unread_count', 1);
});

test('user can mark a notification as read', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(makeAdmin(), ['*']);
    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    Sanctum::actingAs($owner, ['*']);
    $id = $owner->notifications()->first()->id;

    $this->postJson("/api/notifications/{$id}/read")->assertOk();
    expect($owner->unreadNotifications()->count())->toBe(0);
});

test('users only see their own notifications', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(makeAdmin(), ['*']);
    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    Sanctum::actingAs(User::factory()->create(), ['*']);
    $this->getJson('/api/notifications')
        ->assertOk()
        ->assertJsonPath('total_result', 0);
});
