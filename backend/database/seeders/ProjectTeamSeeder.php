<?php

namespace Database\Seeders;

use App\Models\ProjectTeam;
use Illuminate\Database\Seeder;

class ProjectTeamSeeder extends Seeder
{
    public function run()
    {
        // Create 10 project teams
        ProjectTeam::factory()->count(5)->create();
    }
}
