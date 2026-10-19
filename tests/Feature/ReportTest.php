<?php

use App\Models\Report;
use App\Models\User;
use App\Models\Authority;
use App\Models\Area;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $user = User::factory()->create();
    test()->user = $user;
    Sanctum::actingAs($user, ['*']);
});

function actAsAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    test()->user = $admin;
    Sanctum::actingAs($admin, ['*']);

    return $admin;
}

test('index returns only the current users reports', function () {
    Report::factory()->count(3)->create(['user_id' => test()->user->id]);
    Report::factory()->count(5)->create(); // other users' reports

    $response = $this->getJson('/api/reports');
    $response->assertOk();
    $response->assertJsonPath('total_result', 3);
});

test('admin index returns every report', function () {
    actAsAdmin();
    Report::factory()->count(7)->create();

    $response = $this->getJson('/api/reports');
    $response->assertOk();
    $response->assertJsonPath('total_result', 7);
});

test('search is scoped to the current users reports', function () {
    $own = Report::factory()->create(['user_id' => test()->user->id]);
    Report::factory()->create(['title' => $own->title]); // another user, same title

    $response = $this->getJson('/api/reports?filters[title]=' . $own->title);
    $response->assertOk();
    $response->assertJsonPath('total_result', 1);
    $response->assertJsonPath('data.0.id', $own->id);
});

test('user can view their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->getJson('/api/reports/' . $report->id)
        ->assertOk()
        ->assertJsonPath('data.id', $report->id)
        ->assertJsonPath('data.date', $report->date->toISOString())
        ->assertJsonPath('data.title', $report->title);
});

test('user cannot view another users report', function () {
    $report = Report::factory()->create();

    $this->getJson('/api/reports/' . $report->id)->assertForbidden();
});

test('admin can view any report', function () {
    $report = Report::factory()->create();
    actAsAdmin();

    $this->getJson('/api/reports/' . $report->id)->assertOk();
});

test('store report assigns the authenticated user', function () {
    $report = Report::factory()->make();

    $response = $this->postJson('/api/reports', $report->toArray());
    $response->assertOk();

    $this->getJson('/api/reports/' . $response->json('data.id'))
        ->assertOk()
        ->assertJsonPath('data.user_id', test()->user->id)
        ->assertJsonPath('data.authority_id', $report->authority_id)
        ->assertJsonPath('data.area_id', $report->area_id)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.priority', $report->priority)
        ->assertJsonPath('data.type', $report->type)
        ->assertJsonPath('data.title', $report->title);
});

test('user can update their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $payload = Report::factory()->make([
        'area_id' => Area::inRandomOrder()->value('id'),
        'authority_id' => Authority::factory()->create()->id,
    ])->toArray();

    $this->putJson('/api/reports/' . $report->id, $payload)->assertOk();

    $this->getJson('/api/reports/' . $report->id)
        ->assertOk()
        ->assertJsonPath('data.title', $payload['title'])
        ->assertJsonPath('data.area_id', $payload['area_id'])
        ->assertJsonPath('data.authority_id', $payload['authority_id']);
});

test('user cannot update another users report', function () {
    $report = Report::factory()->create();

    $this->putJson('/api/reports/' . $report->id, ['title' => 'hacked'])
        ->assertForbidden();

    $this->assertDatabaseMissing('reports', ['id' => $report->id, 'title' => 'hacked']);
});

test('user can delete their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->deleteJson('/api/reports/' . $report->id)->assertOk();
    $this->assertDatabaseMissing('reports', ['id' => $report->id]);
});

test('user cannot delete another users report', function () {
    $report = Report::factory()->create();

    $this->deleteJson('/api/reports/' . $report->id)->assertForbidden();
    $this->assertDatabaseHas('reports', ['id' => $report->id]);
});
