<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),    // Crea un usuario nuevo automáticamente
            'person_id' => Person::factory(), // Crea una persona nueva automáticamente
        ];
    }
}
