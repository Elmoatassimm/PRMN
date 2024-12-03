<?php

namespace Database\Factories;

use App\Models\ProjectUser;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectUserFactory extends Factory
{
    protected $model = ProjectUser::class;

    public function definition()
    {
        return [
            'role_in_project' => $this->faker->randomElement(['Manager', 'Admin']),
            'project_id' => Project::factory(), // Assumes a ProjectFactory exists
            'user_id' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
