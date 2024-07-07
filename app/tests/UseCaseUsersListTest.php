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
use Test\Mock\TestUserRepository;
use User\UseCase;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseUsersListTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('successedUsersListDataProvider')]
    public function testSuccessedUsersList(?array $list)
    {
        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('findAll')
            ->willReturnOnConsecutiveCalls(
                [],
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

        $this->assertEquals($list, $useCase->getList());
    }

    #[DataProvider('successedUsersListDataProvider')]
    public static function successedUsersListDataProvider(): array
    {
        return [
            [[]],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('failedUsersListDataProvider')]
    public function testFailedUsersList(?array $list)
    {
        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('findAll')
            ->willThrowException(
                new \Exception(),
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

        $this->assertEquals($list, $useCase->getList());
    }

    #[DataProvider('failedUsersListDataProvider')]
    public static function failedUsersListDataProvider(): array
    {
        return [
            [null],
        ];
    }
}