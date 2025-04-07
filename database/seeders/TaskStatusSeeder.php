<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskStatus::create(
           ['name' => TaskStatusEnum::COMPLETED, 'code' => '#2563eb'],
        );
        TaskStatus::create(
            ['name' => TaskStatusEnum::PENDING, 'code' => '#ff0000'],
        );
    }
}
