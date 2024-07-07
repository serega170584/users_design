<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestUserRepositoryRepository;
use User\Dto\User;
use User\Entity\User as DbUser;
use User\Exception\EmailDomainIsNotAllowedException;
use User\Exception\EmailIsNotValidException;
use User\Exception\OnlyNotEmptyIdIsPermittedForUpdateException;
use User\Exception\TooShortNameException;
use User\Exception\WordIsDeniedException;
use User\Validator\UpdateUserValidator;

class UpdateUserValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFailedUpdate(): void
    {
        $user = new User(null, '1', '1', '1');

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

    /**
     * @throws \Exception
     */
    public function testShortNameUpdate(): void
    {
        $user = new User(1, '1', '1', '1');

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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
        $this->expectExceptionObject(new TooShortNameException());
        $validator->validate($user);
    }

    /**
     * @throws \Exception
     */
    public function testInvalidEmailUpdate(): void
    {
        $user = new User(1, '1111111111', 'test', '1');

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

        $this->expectExceptionObject(new EmailIsNotValidException());
        $validator->validate($user);
    }

    /**
     * @throws \Exception
     */
    public function testDeniedWordsUpdate(): void
    {
        $user = new User(1, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

        $deniedWordsStrategy = $this->createMock(TestDeniedWordsStrategy::class);
        $deniedWordsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $this->expectExceptionObject(new WordIsDeniedException());
        $validator->validate($user);
    }

    /**
     * @throws \Exception
     */
    public function testAllowedEmailDomainUpdate(): void
    {
        $user = new User(1, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepositoryRepository::class);
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

        $allowedDomainsStrategy = $this->createMock(TestAllowedDomainsStrategy::class);
        $allowedDomainsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $validator = new UpdateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $this->expectExceptionObject(new EmailDomainIsNotAllowedException());
        $validator->validate($user);
    }
}