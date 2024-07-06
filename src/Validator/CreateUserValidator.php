<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Exception\OnlyEmptyIdIsPermittedForCreateException;
use User\Repository\UserInterface;

class CreateUserValidator
{
    use UserValidatorTrait;

    private UserInterface $userRepository;

    private DeniedWordsStrategyInterface $deniedWordsStrategy;

    private AllowedDomainsStrategyInterface $allowedDomainsStrategy;

    public function  __construct(
        UserInterface $userRepository,
        DeniedWordsStrategyInterface $deniedWordsStrategy,
        AllowedDomainsStrategyInterface $allowedDomainsStrategy
    )
    {
        $this->userRepository = $userRepository;
        $this->deniedWordsStrategy = $deniedWordsStrategy;
        $this->allowedDomainsStrategy = $allowedDomainsStrategy;
    }

    /**
     * @throws \Exception
     */
    public function validate(User $user): void
    {
        if ($user->id !== null) {
            throw new OnlyEmptyIdIsPermittedForCreateException();
        }

        $this->validateUser(
            $user,
            $this->userRepository,
            $this->deniedWordsStrategy,
            $this->allowedDomainsStrategy,
        );
    }

}