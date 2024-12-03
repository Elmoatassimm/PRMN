<?php

namespace Database\Seeders;

use App\Models\TeamTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamTask::factory()->count(8)->create();
    }
}
