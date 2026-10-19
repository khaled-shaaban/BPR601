<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'latitude' => (string) fake()->latitude(),
            'longitude' => (string) fake()->longitude(),
            'active' => true,
        ];
    }
}
