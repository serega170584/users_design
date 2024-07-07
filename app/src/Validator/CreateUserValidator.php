<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Exception\OnlyEmptyIdIsPermittedForCreateException;
use User\Repository\UserRepositoryInterface;

class CreateUserValidator
{
    use UserValidatorTrait;

    private UserRepositoryInterface $userRepository;

    private DeniedWordsStrategyInterface $deniedWordsStrategy;

    private AllowedDomainsStrategyInterface $allowedDomainsStrategy;

    public function  __construct(
        UserRepositoryInterface         $userRepository,
        DeniedWordsStrategyInterface    $deniedWordsStrategy,
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
    public function validate(User $user): bool
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

        return true;
    }

}