<?php

namespace Database\Factories;

use App\Models\InvitedUser;
use App\Models\Project; // Assuming Project is the main invitable model
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitedUserFactory extends Factory
{
    protected $model = InvitedUser::class;

    public function definition()
    {
        return [
            'user_email' => $this->faker->unique()->safeEmail,
            'invitable_id' => Project::factory(), // Link directly to a project factory or create an existing project ID
            'invitable_type' => Project::class, // Set to the model class name
            'invited_by' => User::factory(), // Assumes a UserFactory exists
            'status' => $this->faker->randomElement(['Pending', 'Accepted', 'Rejected']),
        ];
    }
}
