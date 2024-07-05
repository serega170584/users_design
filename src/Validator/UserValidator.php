<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Repository\UserInterface;

class UserValidator
{
    private UserInterface $userRepository;

    private DeniedWordsStrategyInterface $deniedWordsStrategy;

    public function  __construct(UserInterface $userRepository, DeniedWordsStrategyInterface $deniedWordsStrategy)
    {
        $this->userRepository = $userRepository;
        $this->deniedWordsStrategy = $deniedWordsStrategy;
    }

    /**
     * @throws \Exception
     */
    public function validate(User $user): void
    {
        if (!preg_match("#^[a-z0-9]+$#", $user->name)) {
            throw new \Exception('Wrong symbols in name');
        }

        if (strlen($user->name) < 8) {
            throw new \Exception('Too short name');
        }

        if (!$this->deniedWordsStrategy->isValid($user->name)) {
            throw new \Exception('Word is denied');
        }

        if ($this->userRepository->find(['name' => $user->name]) !== null) {
            throw new \Exception('Name is not unique');
        }

        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email is not valid');
        }
    }
}