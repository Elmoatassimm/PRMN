<?php

namespace Database\Seeders;

use App\Models\UserTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserTeam::factory()->count(5)->create();
    }
}
