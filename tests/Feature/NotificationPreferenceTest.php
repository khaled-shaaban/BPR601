<?php

use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportClosed;
use App\Notifications\ReportReceived;
use App\Notifications\ReportStatusChanged;
use Laravel\Sanctum\Sanctum;

function actingStaffAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('the reporter is notified their report was received (on by default)', function () {
    $citizen = User::factory()->create();
    Sanctum::actingAs($citizen, ['*']);

    $this->postJson('/api/reports', Report::factory()->make()->toArray())->assertOk();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $citizen->id,
        'notifiable_type' => User::class,
        'type' => ReportReceived::class,
    ]);
});

test('the received receipt is suppressed when the reporter disabled it', function () {
    $citizen = User::factory()->create(['notify_on_received' => false]);
    Sanctum::actingAs($citizen, ['*']);

    $this->postJson('/api/reports', Report::factory()->make()->toArray())->assertOk();

    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $citizen->id,
        'type' => ReportReceived::class,
    ]);
});

test('closing a report notifies the owner with a distinct closed notification', function () {
    $owner = User::factory()->create();
    $report = Report::factory()->create(['status' => 'in_progress', 'user_id' => $owner->id]);
    Sanctum::actingAs(actingStaffAdmin(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'completed'])->assertOk();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $owner->id,
        'type' => ReportClosed::class,
    ]);
    // A closing transition must NOT also emit a generic status-change notice.
    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $owner->id,
        'type' => ReportStatusChanged::class,
    ]);
});

test('the closed notification is suppressed when the owner disabled it', function () {
    $owner = User::factory()->create(['notify_on_closed' => false]);
    $report = Report::factory()->create(['status' => 'in_progress', 'user_id' => $owner->id]);
    Sanctum::actingAs(actingStaffAdmin(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'completed'])->assertOk();

    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $owner->id,
        'type' => ReportClosed::class,
    ]);
});

test('the status-change notification is suppressed when the owner disabled it', function () {
    $owner = User::factory()->create(['notify_on_status_change' => false]);
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => $owner->id]);
    Sanctum::actingAs(actingStaffAdmin(), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])->assertOk();

    $this->assertDatabaseMissing('notifications', [
        'notifiable_id' => $owner->id,
        'type' => ReportStatusChanged::class,
    ]);
});

test('a user can update their notification preferences', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $this->putJson('/api/auth/notifications', [
        'notify_on_received' => false,
        'notify_on_status_change' => true,
        'notify_on_closed' => false,
    ])->assertOk()
        ->assertJsonPath('data.user.notify_on_received', false)
        ->assertJsonPath('data.user.notify_on_status_change', true)
        ->assertJsonPath('data.user.notify_on_closed', false);

    $fresh = $user->fresh();
    expect($fresh->notify_on_received)->toBeFalse();
    expect($fresh->notify_on_status_change)->toBeTrue();
    expect($fresh->notify_on_closed)->toBeFalse();
});
