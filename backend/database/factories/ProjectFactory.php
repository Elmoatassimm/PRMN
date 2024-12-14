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
        $startDate = Carbon::parse($this->faker->dateTimeThisYear());
        $endDate = Carbon::parse($this->faker->dateTimeBetween($startDate, '+1 year'));
        
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'on_hold', 'completed', 'cancelled']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
