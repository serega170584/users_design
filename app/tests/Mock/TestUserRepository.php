<?php

declare(strict_types=1);

namespace Test\Mock;

use User\Entity\User as DbUser;
use User\Repository\UserInterface;

class TestUserRepository implements UserInterface
{
    function findById(int $id): ?DbUser
    {
        return new DbUser();
    }

    function find(array $filter): ?DbUser
    {
        return new DbUser();
    }

    function findAll(array $filter): array
    {
        return [];
    }
}