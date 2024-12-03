<?php

namespace Database\Factories;

use App\Models\TeamTask;
use App\Models\Team;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamTaskFactory extends Factory
{
    protected $model = TeamTask::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(), // Assumes a TeamFactory exists
            'task_id' => Task::factory(), // Assumes a TaskFactory exists
        ];
    }
}
