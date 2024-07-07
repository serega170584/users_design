<?php

declare(strict_types=1);

namespace User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestEntityManager;
use Test\Mock\TestLogger;
use Test\Mock\TestUserRepository;
use User\Dto\User;
use User\Validator\CreateUserValidator;
use User\Validator\DeleteUserValidator;
use User\Validator\UpdateUserValidator;

class UseCaseTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('createUserDataProvider')]
    public function testCreate(?int $id, string $name, string $email, string $notes, ?int $createdId)
    {
        $user = new User($id,$name, $email, $notes);

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->method('findById')
            ->with([
                'name' => 'namename',
                '!id' => null,
            ])
            ->willReturn(null);

        $repository
            ->method('findById')
            ->with([
                'name' => 'test@test.ru',
                '!id' => null,
            ])
            ->willReturn(null);

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

    public static function createUserDataProvider(): array
    {
        return [
            [1, 'name', 'test@test.ru', '1', null],
            [null, 'name', 'test@test.ru', '1', null],
            [null, 'namename', 'test', '1', null],
            [null, 'namename', 'test@test.ru', '1', 1],
        ];
    }
}