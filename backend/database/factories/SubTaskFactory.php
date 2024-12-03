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
            'title' => json_encode([
                'en' => $this->faker->sentence,
                'fr' => $this->faker->sentence,
                'ar' => $this->faker->sentence,
            ]),
            'is_completed' => 0,
            'task_id' => Task::factory(), // Assumes a TaskFactory exists
        ];
    }
}
