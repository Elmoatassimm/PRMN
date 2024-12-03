<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Create projects
        Project::factory()->count(5)->create(); // Adjust the count as needed
    }
}
