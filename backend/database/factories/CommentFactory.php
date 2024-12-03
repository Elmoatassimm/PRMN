<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->text,
            'project_id' => Project::factory(), // Assumes a ProjectFactory exists
            'user_id' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
