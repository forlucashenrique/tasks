<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::all()->random();

        return [
            'user_id' => $user->id,
            'title' => $this->faker->realText(15, 1),
            'description' => $this->faker->realText(200, 2),
            'finish_date_limit' => $this->faker->dateTimeThisMonth(),
            'finished_date' => $this->faker->dateTimeThisMonth(),
            'excluded_date' => null,
            'finished' => $this->faker->boolean(),
        ];
    }
}
