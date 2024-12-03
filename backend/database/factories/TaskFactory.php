<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => json_encode([
                'en' => $this->faker->sentence,
                'fr' => $this->faker->sentence,
                'ar' => $this->faker->sentence,
            ]),
            'description' => json_encode([
                'en' => $this->faker->paragraph,
                'fr' => $this->faker->paragraph,
                'ar' => $this->faker->paragraph,
            ]),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'project_id' => Project::factory(), // Assumes a ProjectFactory exists
        ];
    }
}
