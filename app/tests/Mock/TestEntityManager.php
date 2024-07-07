<?php

declare(strict_types=1);

namespace Test\Mock;

use User\Entity\User;
use User\EntityManager\EntityManagerInterface;

class TestEntityManager implements EntityManagerInterface
{
    private int $lastId = 0;
    /**
     * @var User[]
     */
    private array $list = [];

    /**
     * @throws \Exception
     */
    public function create(User $user): void
    {
        $this->lastId++;
        $user->setId($this->lastId);
    }

    /**
     * @throws \Exception
     */
    public function update(User $user): void
    {
        printf('update');
    }
}