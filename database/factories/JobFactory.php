<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->jobTitle(),
            "salary" => "$" . number_format(fake()->numberBetween(10000, 1000000)) . " USD",
            "location" => random_int(1, 3) == 2 ? fake()->city() : "Remote",
            "schedule" => random_int(1, 5) == 3 ? "Part Time" : "Full Time",
            "url" => fake()->url(),
            "featured" => random_int(1, 20) == 10 ? true : false,
        ];
    }
}
