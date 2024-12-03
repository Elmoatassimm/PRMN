<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Create 10 tasks
        Task::factory()->count(10)->create();
    }
}
