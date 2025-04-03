<?php

namespace App\DTO;

readonly class TaskDTO
{
    public function __construct(
        public ?int    $id,
        public string  $title,
        public ?string $description,
        public ?string $task_status_id = null,
        public ?int    $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            task_status_id: $data['task_status_id'] ?? null,
            user_id: $data['user_id'] ?? null,
        );
    }
}
