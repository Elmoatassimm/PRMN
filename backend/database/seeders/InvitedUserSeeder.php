<?php

namespace Database\Seeders;

use App\Models\InvitedUser;
use Illuminate\Database\Seeder;

class InvitedUserSeeder extends Seeder
{
    public function run()
    {
        // Create 10 invited users
        InvitedUser::factory()->count(4)->create();
    }
}
