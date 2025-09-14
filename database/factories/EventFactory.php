<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3), // Changed from 'title' to 'name'
            'description' => fake()->paragraph(),
            'start_time' => fake()->dateTimeBetween('now', '+20 days'),
            'end_time' => fake()->dateTimeBetween('+21 days', '+40 days'),
        ];
    }
}
