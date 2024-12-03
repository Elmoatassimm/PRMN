<?php

namespace Database\Factories;

use App\Models\UserTeam;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTeamFactory extends Factory
{
    protected $model = UserTeam::class;

    public function definition()
    {
        return [
            'role' => $this->faker->randomElement(['leader', 'member']),
            'team_id' => Team::factory(), // Assumes a TeamFactory exists
            'user_id' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
