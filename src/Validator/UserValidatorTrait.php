<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Repository\UserInterface;

trait UserValidatorTrait
{
    /**
     * @throws \Exception
     */
    public function validateUser(
        User $user,
        UserInterface $userRepository,
        DeniedWordsStrategyInterface $deniedWordsStrategy,
        AllowedDomainsStrategyInterface $allowedDomainsStrategy
    ): void
    {
        if (!preg_match("#^[a-z0-9]+$#", $user->name)) {
            throw new \Exception('Wrong symbols in name');
        }

        if (strlen($user->name) < 8) {
            throw new \Exception('Too short name');
        }

        if (!$deniedWordsStrategy->isValid($user->name)) {
            throw new \Exception('Word is denied');
        }

        if ($userRepository->find(
                [
                    'name' => $user->name,
                    '!id' => $user->id,
                ]
            ) !== null) {
            throw new \Exception('Name is not unique');
        }

        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email is not valid');
        }

        if ($userRepository->find(
                [
                    'email' => $user->email,
                    '!id' => $user->id
                ]
            ) !== null) {
            throw new \Exception('Email is not unique');
        }

        if (!$allowedDomainsStrategy->isValid($user->email)) {
            throw new \Exception('Email domain is not allowed');
        }
    }
}