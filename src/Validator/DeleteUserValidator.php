<?php

declare(strict_types=1);

namespace User\Validator;

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
    public function validate(int $id, \DateTimeImmutable $delete): void
    {
        $dbUser = $this->userRepository->findById($id);

        if ($delete < $dbUser->getCreated()) {
            throw new CreateMoreThenDeleteException();
        }
    }
}