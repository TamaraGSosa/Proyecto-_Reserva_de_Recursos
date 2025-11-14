<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Resource;
use App\Models\StatusReservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $profile = Profile::inRandomOrder()->first();
        $status_reservation = StatusReservation::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        $start = $this->faker->dateTimeBetween('-1 week', '+1 week');
        $end = $this->faker->dateTimeBetween($start, '+2 week');

        return [
            'status_reservation_id' => $status_reservation->id,
            'profile_id' => $profile->id,
            'create_by_user_id' => $user->id,
            'start_time' => $start,
            'end_time' => $end,
        ];
    }
    
    public function configure()
    {
        return $this->afterCreating(function ($reservation) {
            $resources = Resource::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $reservation->resources()->attach($resources);
        });
    }
}
