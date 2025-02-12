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
use User\Exception\DbFindException;
use User\Exception\DbSaveException;
use User\UseCase;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseCreateUserTest extends TestCase
{
    public function testDbSaveErrorCreateUser()
    {
        $user = new User(1,'asdsadasdasdasd', 'test@test.ru', null);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
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

        $em = $this->createMock(TestEntityManager::class);
        $em
            ->expects($this->any())
            ->method('create')
            ->willThrowException(new DbSaveException());

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertNull($useCase->create($user));
    }

    public function testDbFindErrorCreateUser()
    {
        $user = new User(null,'asdasdasdasd', 'test@test.ru', null);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willThrowException(new DbFindException());

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

        $this->assertNull($useCase->create($user));
    }

    /**
     * @throws Exception
     */
    #[DataProvider('createUniqueUserDataProvider')]
    public function testCreateUniqueUser(?int $id, string $name, string $email, string $notes, ?int $createdId)
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

        $this->assertEquals($createdId, $useCase->create($user));
    }

    public static function createUniqueUserDataProvider(): array
    {
        return [
            [1, 'name', 'test@test.ru', '1', null],
            [null, 'name', 'test@test.ru', '1', null],
            [null, 'namename', 'test', '1', null],
            [null, 'namename', 'test@test.ru', '1', 1],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('createNotUniqueUserNameDataProvider')]
    public function testCreateNotUniqueUserName(?int $id, string $name, string $email, string $notes, ?int $createdId)
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

        $this->assertEquals($createdId, $useCase->create($user));
    }

    public static function createNotUniqueUserNameDataProvider(): array
    {
        return [
            [null, 'namename', 'test@test.ru', '1', null],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('createNotUniqueUserEmailDataProvider')]
    public function testCreateNotUniqueUserEmail(?int $id, string $name, string $email, string $notes, ?int $createdId)
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

        $this->assertEquals($createdId, $useCase->create($user));
    }

    public static function createNotUniqueUserEmailDataProvider(): array
    {
        return [
            [null, 'namename', 'test@test.ru', '1', null],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('createDeniedWordsUserDataProvider')]
    public function testCreateDeniedWordsUser(?int $id, string $name, string $email, string $notes, ?int $createdId)
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

        $this->assertEquals($createdId, $useCase->create($user));
    }

    public static function createDeniedWordsUserDataProvider(): array
    {
        return [
            [null, 'namename', 'test@test.ru', '1', null],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('createAllowedDomainsUserDataProvider')]
    public function testCreateAllowedDomainsUser(?int $id, string $name, string $email, string $notes, ?int $createdId)
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

        $this->assertEquals($createdId, $useCase->create($user));
    }

    public static function createAllowedDomainsUserDataProvider(): array
    {
        return [
            [null, 'namename', 'test@test.ru', '1', null],
        ];
    }
}