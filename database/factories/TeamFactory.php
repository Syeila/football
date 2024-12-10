<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl(640, 480, 'sports'),
            'founded_year' => $this->faker->year,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
        ];
    }
}
