<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Area>
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition(): array
    {
        return [
            'name' => fake()->streetName(),
            'code' => strtoupper(fake()->unique()->bothify('???_###')),
            'latitude' => (string) fake()->latitude(),
            'longitude' => (string) fake()->longitude(),
            'city_id' => City::inRandomOrder()->value('id') ?? City::factory(),
            'active' => true,
        ];
    }
}
