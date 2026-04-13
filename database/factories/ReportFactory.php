<?php 

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Authority;
use App\Enums\ReportStatus;
use App\Enums\ReportPriority;
use App\Enums\ReportTypes;

class ReportFactory extends Factory
{
    protected $model = Report::class;
    
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'authority_id' => Authority::factory(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'status' => array_rand(ReportStatus::cases()),
            'priority' => array_rand(ReportPriority::cases()),
            'type' => array_rand(ReportTypes::cases()),
            'date' => fake()->dateTime(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}