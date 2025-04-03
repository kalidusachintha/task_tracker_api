<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => TaskStatusEnum::PENDING->value,
            'code' => $this->faker->hexColor
        ];
    }
}
