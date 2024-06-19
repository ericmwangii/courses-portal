<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'type' => $this->faker->randomElement(['lesson', 'comment']),
            'threshold' => $this->faker->randomDigitNotNull,
        ];
    }
}

