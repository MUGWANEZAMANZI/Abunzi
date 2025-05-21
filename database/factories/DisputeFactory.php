<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dispute>
 */
class DisputeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'offender_name' => $this->faker->name,
            'offender_mail' => $this->faker->email,
            'witness_name' => $this->faker->name,
            'province' => $this->faker->state,
            'district' => $this->faker->city,
            'sector' => $this->faker->word,
            'cell' => $this->faker->word,
            'village' => $this->faker->word,
            'status' => $this->faker->randomElement(['cyoherejwe', 'kirabitse', 'kizasomwa', 'cyakemutse']),
            'citizen_id' => \App\Models\User::factory(),
            'location_name' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
