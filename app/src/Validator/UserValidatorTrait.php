<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Exception\EmailDomainIsNotAllowedException;
use User\Exception\EmailIsNotUniqueException;
use User\Exception\EmailIsNotValidException;
use User\Exception\NameIsNotUniqueException;
use User\Exception\TooShortNameException;
use User\Exception\WordIsDeniedException;
use User\Exception\WrongSymbolsException;
use User\Repository\UserRepositoryInterface;

trait UserValidatorTrait
{
    /**
     * @throws \Exception
     */
    public function validateUser(
        User                            $user,
        UserRepositoryInterface         $userRepository,
        DeniedWordsStrategyInterface    $deniedWordsStrategy,
        AllowedDomainsStrategyInterface $allowedDomainsStrategy
    ): void
    {
        if (!preg_match("#^[a-z0-9]+$#", $user->name)) {
            throw new WrongSymbolsException();
        }

        if (strlen($user->name) < 8) {
            throw new TooShortNameException();
        }

        if (!$deniedWordsStrategy->isValid($user->name)) {
            throw new WordIsDeniedException();
        }

        if ($userRepository->find(
                [
                    'name' => $user->name,
                    '!id' => $user->id,
                ]
            ) !== null) {
            throw new NameIsNotUniqueException();
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailIsNotValidException();
        }

        if ($userRepository->find(
                [
                    'email' => $user->email,
                    '!id' => $user->id
                ]
            ) !== null) {
            throw new EmailIsNotUniqueException();
        }

        if (!$allowedDomainsStrategy->isValid($user->email)) {
            throw new EmailDomainIsNotAllowedException();
        }
    }
}