<?php

declare(strict_types=1);

namespace User\Validator;

use User\Entity\User;
use User\Exception\CreateMoreThenDeleteException;
use User\Repository\UserInterface;

class DeleteUserValidator
{
    private UserInterface $userRepository;

    public function  __construct(
        UserInterface $userRepository,
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