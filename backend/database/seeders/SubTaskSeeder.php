<?php

namespace Database\Seeders;

use App\Models\SubTask;
use Illuminate\Database\Seeder;

class SubTaskSeeder extends Seeder
{
    public function run()
    {
        // Create 10 subtasks
        SubTask::factory()->count(35)->create();
    }
}
