<?php

use App\Models\Authority;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function makeRep(int $authorityId): User
{
    $rep = User::factory()->create(['authority_id' => $authorityId]);
    $rep->assignRole('representative');

    return $rep;
}

test('a representative sees only their authority reports', function () {
    $authorityA = Authority::factory()->create();
    $authorityB = Authority::factory()->create();
    Report::factory()->count(3)->create(['authority_id' => $authorityA->id]);
    Report::factory()->count(2)->create(['authority_id' => $authorityB->id]);

    Sanctum::actingAs(makeRep($authorityA->id), ['*']);

    $this->getJson('/api/reports')
        ->assertOk()
        ->assertJsonPath('total_result', 3);
});

test('a representative can view a report for their authority', function () {
    $authority = Authority::factory()->create();
    $report = Report::factory()->create(['authority_id' => $authority->id]);
    Sanctum::actingAs(makeRep($authority->id), ['*']);

    $this->getJson("/api/reports/{$report->id}")->assertOk();
});

test('a representative cannot view another authority report', function () {
    $report = Report::factory()->create();
    $other = Authority::factory()->create();
    Sanctum::actingAs(makeRep($other->id), ['*']);

    $this->getJson("/api/reports/{$report->id}")->assertForbidden();
});

test('a representative can change status and adds a separate representative note', function () {
    $authority = Authority::factory()->create();
    $report = Report::factory()->create(['authority_id' => $authority->id, 'status' => 'pending']);
    Sanctum::actingAs(makeRep($authority->id), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress', 'note' => 'Dispatched a unit'])
        ->assertOk()
        ->assertJsonPath('data.status', 'in_progress');

    // The note lands in the separate representative-notes section, NOT the shared timeline.
    $this->assertDatabaseHas('representative_notes', ['report_id' => $report->id, 'body' => 'Dispatched a unit']);
    $this->assertDatabaseMissing('report_comments', ['report_id' => $report->id, 'body' => 'Dispatched a unit']);
});

test('a representative cannot change status on another authority report', function () {
    $report = Report::factory()->create(['status' => 'pending']);
    $other = Authority::factory()->create();
    Sanctum::actingAs(makeRep($other->id), ['*']);

    $this->patchJson("/api/reports/{$report->id}/status", ['status' => 'in_progress'])
        ->assertForbidden();
});

test('a representative can add and list representative notes for their report', function () {
    $authority = Authority::factory()->create();
    $report = Report::factory()->create(['authority_id' => $authority->id]);
    Sanctum::actingAs(makeRep($authority->id), ['*']);

    $this->postJson("/api/reports/{$report->id}/representative-notes", ['body' => 'Investigating'])
        ->assertOk()
        ->assertJsonPath('data.body', 'Investigating');

    $this->getJson("/api/reports/{$report->id}/representative-notes")
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('representatives can submit reports', function () {
    $authority = Authority::factory()->create();
    Sanctum::actingAs(makeRep($authority->id), ['*']);

    $payload = Report::factory()->make(['authority_id' => $authority->id])->toArray();

    $this->postJson('/api/reports', $payload)->assertOk();
});

test('a representative also sees reports they filed themselves', function () {
    $authorityA = Authority::factory()->create();
    $otherAuthority = Authority::factory()->create();
    $rep = makeRep($authorityA->id);

    // A report the rep filed to a different authority still appears in their list.
    Report::factory()->create(['authority_id' => $otherAuthority->id, 'user_id' => $rep->id]);

    Sanctum::actingAs($rep, ['*']);
    $this->getJson('/api/reports')->assertOk()->assertJsonPath('total_result', 1);
});
