<?php

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Storage::fake('public');
    $user = User::factory()->create();
    test()->user = $user;
    Sanctum::actingAs($user, ['*']);
});

test('user can upload media to their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $response = $this->post("/api/reports/{$report->id}/media", [
        'files' => [
            UploadedFile::fake()->image('photo.jpg'),
            UploadedFile::fake()->create('evidence.pdf', 120, 'application/pdf'),
        ],
    ]);

    $response->assertOk();
    expect($report->media()->count())->toBe(2);

    foreach ($report->media()->pluck('media_url') as $path) {
        Storage::disk('public')->assertExists($path);
    }
});

test('user can list media for their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);
    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->image('a.jpg')],
    ])->assertOk();

    $response = $this->getJson("/api/reports/{$report->id}/media");
    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.media_name', 'a.jpg');
});

test('uploaded media is included on the report show response', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);
    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->image('a.jpg')],
    ])->assertOk();

    $this->getJson("/api/reports/{$report->id}")
        ->assertOk()
        ->assertJsonCount(1, 'data.media');
});

test('upload requires at least one file', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->postJson("/api/reports/{$report->id}/media", [])->assertStatus(422);
});

test('upload rejects disallowed file types', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);

    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload')],
    ], ['Accept' => 'application/json'])->assertStatus(422);
});

test('user cannot upload media to another users report', function () {
    $report = Report::factory()->create();

    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->image('x.jpg')],
    ])->assertForbidden();
});

test('user can delete media from their own report', function () {
    $report = Report::factory()->create(['user_id' => test()->user->id]);
    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->image('a.jpg')],
    ])->assertOk();
    $media = $report->media()->first();

    $this->deleteJson("/api/reports/{$report->id}/media/{$media->id}")->assertOk();
    $this->assertDatabaseMissing('report_media', ['id' => $media->id]);
    Storage::disk('public')->assertMissing($media->media_url);
});

test('admin can upload media to any report', function () {
    $report = Report::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Sanctum::actingAs($admin, ['*']);

    $this->post("/api/reports/{$report->id}/media", [
        'files' => [UploadedFile::fake()->image('a.jpg')],
    ])->assertOk();

    expect($report->media()->count())->toBe(1);
});
