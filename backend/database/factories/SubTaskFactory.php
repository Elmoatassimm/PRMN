<?php

namespace Database\Factories;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubTaskFactory extends Factory
{
    protected $model = Subtask::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'task_id' => Task::factory(), // Assumes a TaskFactory exists
        ];
    }
}
