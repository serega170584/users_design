<?php

declare(strict_types=1);

namespace User\EntityManager;

use User\Entity\User;

interface EntityManagerInterface
{
    public function create(User $user): void;
    public function update(User $user): void;
}