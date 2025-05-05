<?php

namespace Database\Factories;

use App\Constants;
use DateTime;
use Illuminate\Support\Arr;
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
            "schedule" => Arr::random(Constants::SCHEDULES),
            "url" => fake()->url(),
            "featured" => random_int(1, 20) == 10 ? true : false,
            "employer_id" => \App\Models\Employer::factory(),
            'created_at' => ($date = fake()->dateTimeBetween('-1 year', 'now')),
            'updated_at' => (clone $date)->modify('+' . (random_int(0, 1) ? random_int(1, 43200) : 0) . ' minutes'),
        ];
    }
}
