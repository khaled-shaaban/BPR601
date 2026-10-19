<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Authority;
use App\Enums\ReportStatus;
use App\Enums\ReportPriority;
use App\Enums\ReportTypes;
use App\Models\Area;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'authority_id' => Authority::factory(),
            'area_id' => Area::inRandomOrder()->value('id') ?? Area::factory(),
            'latitude' => (string) fake()->latitude(),
            'longitude' => (string) fake()->longitude(),
            'status' => fake()->randomElement(ReportStatus::cases())->value,
            'priority' => fake()->randomElement(ReportPriority::cases())->value,
            'type' => fake()->randomElement(ReportTypes::cases())->value,
            'date' => fake()->dateTime(),
            'title' => fake()->sentence(),
            'description' => fake()->text(255),
        ];
    }
}
