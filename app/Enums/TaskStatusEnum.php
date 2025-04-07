<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}
