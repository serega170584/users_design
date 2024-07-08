<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestEntityManager;
use Test\Mock\TestLogger;
use Test\Mock\TestUserRepositoryRepository;
use User\Dto\User;
use User\Entity\User as DbUser;
use User\UseCase;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseUpdateUserTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('successedUpdateUserDataProvider')]
    public function testSuccessedUpdateUser(?int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id,$name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
                null,
            );

        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                new DbUser(),
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function successedUpdateUserDataProvider(): array
    {
        return [
            [null, 'name', 'test@test.ru', '1', false],
            [1, 'name', 'test@test.ru', '1', false],
            [1, 'namename', 'test', '1', false],
            [1, 'namename', 'test@test.ru', '1', true],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('updateInvalidUserDataProvider')]
    public function testUpdateInvalidUser(?int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id,$name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
                null,
            );

        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function updateInvalidUserDataProvider(): array
    {
        return [
            [1, 'name', 'test@test.ru', '1', false],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('updateNotUniqueUserNameDataProvider')]
    public function testUpdateNotUniqueUserName(int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id,$name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $dbUser = new DbUser();
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                $dbUser,
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function updateNotUniqueUserNameDataProvider(): array
    {
        return [
            [1, 'namename', 'test@test.ru', '1', false],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('updateNotUniqueUserEmailDataProvider')]
    public function testUpdateNotUniqueUserEmail(int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id,$name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $dbUser = new DbUser();
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
                $dbUser,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function updateNotUniqueUserEmailDataProvider(): array
    {
        return [
            [1, 'namename', 'test@test.ru', '1', false],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('updateDeniedWordsUserDataProvider')]
    public function testUpdateDeniedWordsUser(int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id, $name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
                null,
            );

        $deniedWordsStrategy = $this->createMock(TestDeniedWordsStrategy::class);
        $deniedWordsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function updateDeniedWordsUserDataProvider(): array
    {
        return [
            [1, 'namename', 'test@test.ru', '1', false],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('updateAllowedDomainsUserDataProvider')]
    public function testUpdateAllowedDomainsUser(int $id, string $name, string $email, string $notes, bool $isUpdated)
    {
        $user = new User($id, $name, $email, $notes);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();

        $allowedDomainsStrategy = $this->createMock(TestAllowedDomainsStrategy::class);
        $allowedDomainsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $createUserValidator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $updateUserValidator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $deleteUserValidator = new DeleteUserValidator(
            $repository,
        );

        $logger = new TestLogger();

        $em = new TestEntityManager();

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertEquals($isUpdated, $useCase->update($user));
    }

    public static function updateAllowedDomainsUserDataProvider(): array
    {
        return [
            [1, 'namename', 'test@test.ru', '1', false],
        ];
    }
}