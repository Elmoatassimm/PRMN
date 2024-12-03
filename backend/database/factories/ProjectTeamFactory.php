<?php

namespace Database\Factories;

use App\Models\ProjectTeam;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectTeamFactory extends Factory
{
    protected $model = ProjectTeam::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(), // Assumes a ProjectFactory exists
            'team_id' => Team::factory(), // Assumes a TeamFactory exists
        ];
    }
}
