<?php

declare(strict_types=1);

namespace User\Repository;

use User\Dto\User;
use User\Entity\User as DbUser;

interface UserInterface
{
    function create(User $user): DbUser;
    function findById(int $id): ?DbUser;

    function find(array $filter): ?DbUser;
}