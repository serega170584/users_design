<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Exception\OnlyNotEmptyIdIsPermittedForUpdateException;
use User\Exception\UserIsNotExistedException;
use User\Repository\UserRepositoryInterface;

final class UpdateUserValidator
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
        if ($user->id === null) {
            throw new OnlyNotEmptyIdIsPermittedForUpdateException();
        }

        if ($this->userRepository->findById($user->id) === null) {
            throw new UserIsNotExistedException();
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