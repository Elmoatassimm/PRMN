<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'type' => $this->faker->word,
            'data' => json_encode([
                'message' => $this->faker->sentence,
                'link' => $this->faker->url,
            ]),
            'user_id' => User::factory(), // Assumes a UserFactory exists
        ];
    }
}
