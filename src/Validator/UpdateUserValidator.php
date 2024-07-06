<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;
use User\Repository\UserInterface;

class UpdateUserValidator
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
        if ($user->id === null) {
            throw new \Exception('Only not empty id is permitted for update');
        }

        if ($this->userRepository->findById($user->id) !== null) {
            throw new \Exception(sprintf('User with id %s is not existed', $user->id));
        }

        $this->validateUser(
            $user,
            $this->userRepository,
            $this->deniedWordsStrategy,
            $this->allowedDomainsStrategy,
        );
    }

}