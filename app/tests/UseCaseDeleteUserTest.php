<?php

declare(strict_types=1);

namespace Test;

use DateInterval;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestEntityManager;
use Test\Mock\TestLogger;
use Test\Mock\TestUserRepositoryRepository;
use User\Entity\User;
use User\Exception\DbFindException;
use User\Exception\DbSaveException;
use User\UseCase;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseDeleteUserTest extends TestCase
{
    public function testDbSaveErrorDeleteUser()
    {
        $interval = new DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->sub($interval);

        $user = new User();
        $user->setId(1);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                $user,
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
            ->method('update')
            ->willThrowException(new DbSaveException());

        $useCase = new UseCase(
            $createUserValidator,
            $updateUserValidator,
            $deleteUserValidator,
            $logger,
            $em,
            $repository,
        );

        $this->assertFalse($useCase->delete(1));
    }

    public function testDbFindByErrorDeleteUser()
    {
        $interval = new DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->sub($interval);

        $user = new User();
        $user->setId(1);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('findById')
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

        $this->assertFalse($useCase->delete(1));
    }

    /**
     * @throws Exception
     */
    #[DataProvider('successedDeleteUserDataProvider')]
    public function testSuccessedDeleteUser(int $id, bool $isDeleted)
    {
        $interval = new DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->sub($interval);

        $user = new User();
        $user->setId($id);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                $user,
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

        $this->assertEquals($isDeleted, $useCase->delete($id));
    }

    public static function successedDeleteUserDataProvider(): array
    {
        return [
            [1, true],
        ];
    }

    #[DataProvider('failedDeleteDateUserDataProvider')]
    public function testFailedDeleteDateDeleteUser(int $id, bool $isDeleted)
    {
        $interval = new DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->add($interval);

        $user = new User();
        $user->setId($id);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepositoryRepository::class);
        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                $user,
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

        $this->assertEquals($isDeleted, $useCase->delete($id));
    }

    public static function failedDeleteDateUserDataProvider(): array
    {
        return [
            [1, false],
        ];
    }

    #[DataProvider('failedUserDeleteUserDataProvider')]
    public function testFailedUserDeleteUser(int $id, bool $isDeleted)
    {
        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

        $this->assertEquals($isDeleted, $useCase->delete($id));
    }

    public static function failedUserDeleteUserDataProvider(): array
    {
        return [
            [1, false],
        ];
    }
}