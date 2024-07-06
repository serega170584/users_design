<?php

declare(strict_types=1);

namespace User\Dto;

readonly class User
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $notes,
    ) {}
}