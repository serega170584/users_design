<?php

declare(strict_types=1);

namespace User;

use User\Dto\User;
use User\EntityManager\EntityManagerInterface;
use User\Logger\LoggerInterface;
use User\Repository\UserInterface;
use User\Validator\Creator;

class UseCase
{
    public function __construct(
        readonly Creator $creatorValidator,
        readonly LoggerInterface $logger,
        readonly EntityManagerInterface $entityManager,
        readonly UserInterface $userRepository,
        public ?string  $notes,
    ) {}
    public function create(User $user): ?int {
        try {
            $this->creatorValidator->validate($user);

            $dbUser = $this->userRepository->create($user);

            $this->logger->info("Database user create start");

            $this->entityManager->update($dbUser);

            $this->logger->info("Database user create start");

            return $dbUser->getId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }
    }
}