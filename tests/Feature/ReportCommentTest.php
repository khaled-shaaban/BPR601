<?php

use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $user = User::factory()->create();
    test()->user = $user;
    Sanctum::actingAs($user, ['*']);
});

test('user can add a comment to their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->postJson("/api/reports/{$report->id}/comments", ['body' => 'Any update?'])
        ->assertOk()
        ->assertJsonPath('data.body', 'Any update?')
        ->assertJsonPath('data.type', 'comment')
        ->assertJsonPath('data.user_id', test()->user->id);

    $this->assertDatabaseHas('report_comments', [
        'report_id' => $report->id,
        'body' => 'Any update?',
        'type' => 'comment',
    ]);
});

test('user can list the timeline of their own report in order', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);
    $this->postJson("/api/reports/{$report->id}/comments", ['body' => 'first'])->assertOk();
    $this->postJson("/api/reports/{$report->id}/comments", ['body' => 'second'])->assertOk();

    $this->getJson("/api/reports/{$report->id}/comments")
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.body', 'first')
        ->assertJsonPath('data.1.body', 'second');
});

test('comment requires a body', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->postJson("/api/reports/{$report->id}/comments", [])->assertStatus(422);
});

test('user cannot comment on another users report', function () {
    $report = Report::factory()->create();

    $this->postJson("/api/reports/{$report->id}/comments", ['body' => 'x'])->assertForbidden();
});

test('changing status records a status_change entry on the timeline', function () {
    $report = Report::factory()->create(['status' => 'pending', 'user_id' => test()->user->id]);

    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", [
        'status' => 'in_progress',
        'note' => 'Dispatching a unit',
    ])->assertOk();

    Sanctum::actingAs(test()->user, ['*']);
    $response = $this->getJson("/api/reports/{$report->id}/comments")->assertOk();

    $response->assertJsonPath('data.0.type', 'status_change');
    expect($response->json('data.0.body'))->toContain('in_progress');
    expect($response->json('data.0.body'))->toContain('Dispatching a unit');
});

test('assigning a report records an assignment entry on the timeline', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $handler = User::factory()->create(['name' => 'Handler Hana']);
    $handler->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->patchJson("/api/reports/{$report->id}/assign", ['assigned_to' => $handler->id])->assertOk();

    Sanctum::actingAs(test()->user, ['*']);
    $this->getJson("/api/reports/{$report->id}/comments")
        ->assertOk()
        ->assertJsonPath('data.0.type', 'assignment')
        ->assertJsonPath('data.0.body', 'Assigned to Handler Hana.');
});
