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
        $randomSchedule = random_int(1, 10);

        return [
            "title" => fake()->jobTitle(),
            "salary" => "$" . number_format(fake()->numberBetween(10000, 1000000)) . " USD",
            "location" => random_int(1, 3) == 2 ? fake()->city() : "Remote",
            "schedule" => $randomSchedule > 7 ? "Part Time" : ($randomSchedule > 5 ? "Freelance" : "Full Time"),
            "url" => fake()->url(),
            "featured" => random_int(1, 20) == 10 ? true : false,
        ];
    }
}
