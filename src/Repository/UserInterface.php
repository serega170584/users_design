<?php

declare(strict_types=1);

namespace User\Repository;

use User\Entity\User as DbUser;

interface UserInterface
{
    function findById(int $id): ?DbUser;

    function find(array $filter): ?DbUser;

    /**
     * @return DbUser[]
     */
    function findAll(array $filter): array;
}