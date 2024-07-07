<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestUserRepository;
use User\Dto\User;
use User\Entity\User as DbUser;
use User\Exception\OnlyNotEmptyIdIsPermittedForUpdateException;
use User\Validator\UpdateUserValidator;

class UpdateUserValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFailedUpdate(): void
    {
        $user = new User(null, '1', '1', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('findById')
            ->willReturnOnConsecutiveCalls(
                new DbUser(),
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );
        $this->expectExceptionObject(new OnlyNotEmptyIdIsPermittedForUpdateException());
        $validator->validate($user);
    }

    /**
     * @throws \Exception
     */
    public function testSuccessedUpdate(): void
    {
        $user = new User(1, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
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

        $validator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $this->assertTrue($validator->validate($user));
    }
}