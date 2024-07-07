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
use Test\Mock\TestUserRepository;
use User\Entity\User;
use User\UseCase;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseDeleteUserTest extends TestCase
{
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
        $user->setId(1);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepository::class);
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

    #[DataProvider('failedDeleteUserDataProvider')]
    public function testFailedDeleteUser(int $id, bool $isDeleted)
    {
        $interval = new DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->add($interval);

        $user = new User();
        $user->setId(1);
        $user->setCreated($created);

        $repository = $this->createMock(TestUserRepository::class);
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

    public static function failedDeleteUserDataProvider(): array
    {
        return [
            [1, false],
        ];
    }
}