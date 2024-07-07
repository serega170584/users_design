<?php

declare(strict_types=1);

namespace User;

use User\Dto\User;
use User\Entity\User as DbUser;
use User\EntityManager\EntityManagerInterface;
use User\Logger\LoggerInterface;
use User\Repository\UserRepositoryInterface;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCase
{
    private const USER_CREATE_MARK = 'User create';
    private const USER_UPDATE_MARK = 'User update';
    private const USER_DELETE_MARK = 'User delete';
    private const USERS_LIST_MARK = 'Users list';

    private const EXCEPTION_PARAM_NAME = 'exception';
    private const ID_PARAM_NAME = 'id';

    private CreateUserValidator $createUserValidator;
    private UpdateUserValidator $updateUserValidator;
    private DeleteUserValidator $deleteUserValidator;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        CreateUserValidator     $createUserValidator,
        UpdateUserValidator     $updateUserValidator,
        DeleteUserValidator     $deleteUserValidator,
        LoggerInterface         $logger,
        EntityManagerInterface  $entityManager,
        UserRepositoryInterface $userRepository,
    ) {
        $this->createUserValidator = $createUserValidator;
        $this->updateUserValidator = $updateUserValidator;
        $this->deleteUserValidator = $deleteUserValidator;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    public function create(User $user): ?int {
        try {

            $this->createUserValidator->validate($user);

            $dbUser = $this->createUserEntity($user);

            $this->logger->info(sprintf('[%s] database user create start', self::USER_CREATE_MARK));

            $this->entityManager->create($dbUser);

            $this->logger->info(sprintf('[%s] database user create finish', self::USER_CREATE_MARK));

            return $dbUser->getId();
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            $this->logger->error(sprintf('[%s]', self::USER_CREATE_MARK), [
                self::EXCEPTION_PARAM_NAME => $e,
            ]);

            return null;
        }
    }

    public function update(User $user): bool {
        try {
            $this->updateUserValidator->validate($user);

            $dbUser = $this->updateUserEntity($user);

            $this->logger->info(sprintf('[%s] database user update start', self::USER_UPDATE_MARK));

            $this->entityManager->update($dbUser);

            $this->logger->info(sprintf('[%s] database user create finish', self::USER_UPDATE_MARK));

            return true;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[%s]', self::USER_UPDATE_MARK), [
                self::EXCEPTION_PARAM_NAME => $e,
                self::ID_PARAM_NAME => $user->id,
            ]);

            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $date = new \DateTimeImmutable();

            $dbUser = $this->userRepository->findById($id);

            $this->deleteUserValidator->validate($dbUser, $date);

            $dbUser->setDeleted($date);

            $this->logger->info(sprintf('[%s] database user delete start', self::USER_DELETE_MARK));

            $this->entityManager->update($dbUser);

            $this->logger->info(sprintf('[%s] database user delete finish', self::USER_DELETE_MARK));
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[%s]', self::USER_DELETE_MARK), [
                self::EXCEPTION_PARAM_NAME => $e,
                self::ID_PARAM_NAME => $id,
            ]);

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
            $this->logger->info(sprintf('[%s] database users list start', self::USERS_LIST_MARK));

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

            $this->logger->info(sprintf('[%s] database users list finish', self::USERS_LIST_MARK));
        } catch (\Exception $e) {
            $this->logger->error(sprintf('%s]', self::USERS_LIST_MARK), [
                self::EXCEPTION_PARAM_NAME => $e,
            ]);

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