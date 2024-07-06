<?php

declare(strict_types=1);

namespace User\EntityManager;

use User\Entity\User;

interface EntityManagerInterface
{
    public function update(User $user): void;
}