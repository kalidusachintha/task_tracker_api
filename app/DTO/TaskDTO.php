<?php

namespace App\DTO;

use App\Enums\TaskStatus;

readonly class TaskDTO
{
    public function __construct(
        public ?int $id,
        public string $title,
        public ?string $description,
        public string $status = 'pending',
        public ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            status: $data['status'] ?? TaskStatus::PENDING,
            user_id: $data['user_id'] ?? null,
        );
    }
}
