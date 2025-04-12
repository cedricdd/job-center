<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'url' => fake()->url(),
            'description' => fake()->text(500),
            'logo' => "https://picsum.photos/seed/" . rand(1, 1000000) . "/250",
            "user_id" => \App\Models\User::factory(),
        ];
    }
}
