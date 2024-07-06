<?php

declare(strict_types=1);

namespace User;

use User\Dto\User;
use \User\Entity\User as DbUser;
use User\EntityManager\EntityManagerInterface;
use User\Logger\LoggerInterface;
use User\Repository\UserInterface;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCase
{
    public function __construct(
        private CreateUserValidator     $createUserValidator,
        private UpdateUserValidator     $updateUserValidator,
        private DeleteUserValidator $deleteUserValidator,
        private LoggerInterface        $logger,
        private EntityManagerInterface $entityManager,
        private UserInterface          $userRepository,
    ) {}
    public function create(User $user): ?int {
        try {
            $this->createUserValidator->validate($user);

            $dbUser = $this->createUserEntity($user);

            $this->logger->info("Database user create start");

            $this->entityManager->update($dbUser);

            $this->logger->info("Database user create start");

            return $dbUser->getId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }
    }

    public function update(User $user): bool {
        try {
            $this->updateUserValidator->validate($user);

            $dbUser = $this->updateUserEntity($user);

            $this->logger->info("Database user update start");

            $this->entityManager->update($dbUser);

            $this->logger->info("Database user create start");

            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $date = new \DateTimeImmutable();

            $dbUser = $this->userRepository->findById($id);

            $this->deleteUserValidator->validate($id, $date);

            $dbUser->setDeleted(new \DateTimeImmutable());

            $this->entityManager->update($dbUser);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @return DbUser[]|null
     */
    public function getList(): ?array
    {
        $users = [];

        try {
            $dbUsers = $this->userRepository->findAll(['deleted' => null]);
            foreach ($dbUsers as $dbUser) {
                $user = new User(
                    $dbUser->getId(),
                    $dbUser->getName(),
                    $dbUser->getEmail(),
                    $dbUser->getNotes(),
                );
                $users[] = $user;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }

        return $users;
    }

    private function createUserEntity(User $user): DbUser
    {
        $dbUser = new DbUser();
        $dbUser->setName($user->name);
        $dbUser->setEmail($user->email);
        $dbUser->setNotes($user->notes);

        return $dbUser;
    }

    /**
     * @throws \Exception
     */
    private function updateUserEntity(User $user): DbUser
    {
        $dbUser = new DbUser();
        $dbUser->setId($user->id);
        $dbUser->setName($user->name);
        $dbUser->setEmail($user->email);
        $dbUser->setNotes($user->notes);

        return $dbUser;
    }


}