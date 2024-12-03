<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class,
            SubTaskSeeder::class,
            NotificationSeeder::class,
            CommentSeeder::class,
            TeamSeeder::class,
            TeamTaskSeeder::class,
            InvitedUserSeeder::class,
            ProjectUserSeeder::class,
            UserTeamSeeder::class,
            ProjectTeamSeeder::class,

        ]);
    }
}
