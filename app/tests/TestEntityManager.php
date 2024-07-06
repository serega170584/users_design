<?php

declare(strict_types=1);

namespace User;

use User\Entity\User;
use User\EntityManager\EntityManagerInterface;

class TestEntityManager implements EntityManagerInterface
{
    public function update(User $user): void
    {
        printf('Updated');
    }
}