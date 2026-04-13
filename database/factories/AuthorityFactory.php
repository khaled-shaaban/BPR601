<?php 

namespace Database\Factories;

use App\Models\Authority;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorityFactory extends Factory
{
    protected $model = Authority::class;
    
    public function definition(): array
    {
        $area = Area::first();
        
        return [    
            'name' => fake()->name(),
            'code' => fake()->unique()->randomNumber(5),
            'description' => fake()->sentence(),
            'icon' => fake()->imageUrl(),
            'color' => fake()->colorName(),
            'area_id' => $area->id,
            'address' => fake()->address(),
            'contact_phone_1' => fake()->phoneNumber(),
            'contact_phone_2' => fake()->phoneNumber(),
            'active' => fake()->boolean(),
        ];
    }
}