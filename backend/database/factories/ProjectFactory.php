<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'name' => json_encode([
                'en' => $this->faker->sentence,
                'fr' => $this->faker->sentence,
                'ar' => $this->faker->sentence,
            ]),
            'description' => json_encode([
                'en' => $this->faker->paragraph,
                'fr' => $this->faker->paragraph,
                'ar' => $this->faker->paragraph,
            ]),
            'start_date' => Carbon::parse($this->faker->dateTimeThisYear()),
            'end_date' => Carbon::parse($this->faker->dateTimeBetween('now', '+1 year')),
            'created_by' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
