<?php

declare(strict_types=1);

namespace User\Validator;

use User\Entity\User;
use User\Exception\CreateMoreThenDeleteException;
use User\Repository\UserRepositoryInterface;

final class DeleteUserValidator
{
    private UserRepositoryInterface $userRepository;

    public function  __construct(
        UserRepositoryInterface $userRepository,
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function validate(User $user, \DateTimeImmutable $delete): bool
    {
        if ($delete < $user->getCreated()) {
            throw new CreateMoreThenDeleteException();
        }

        return true;
    }
}