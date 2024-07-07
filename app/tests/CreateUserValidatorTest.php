<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestUserRepository;
use User\Dto\User;
use User\Exception\EmailDomainIsNotAllowedException;
use User\Exception\EmailIsNotValidException;
use User\Exception\OnlyEmptyIdIsPermittedForCreateException;
use User\Exception\TooShortNameException;
use User\Exception\WordIsDeniedException;
use User\Validator\CreateUserValidator;

class CreateUserValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFailedCreate(): void
    {
        $user = new User(1, '1', '1', '1');

        $repository = new TestUserRepository();
        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );
        $this->expectExceptionObject(new OnlyEmptyIdIsPermittedForCreateException);
        $validator->validate($user);
    }

    /**
     * @throws \Exception
     */
    public function testSuccessedCreate(): void
    {
        $user = new User(null, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $this->assertTrue($validator->validate($user));
    }

    /**
     * @throws \Exception
     */
    public function testShortNameCreate(): void
    {
        $user = new User(null, '1', '1', '1');

        $repository = new TestUserRepository();
        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new CreateUserValidator(
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
    public function testInvalidEmailCreate(): void
    {
        $user = new User(null, '1111111111', 'test', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();
        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new CreateUserValidator(
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
    public function testDeniedWordsCreate(): void
    {
        $user = new User(null, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
            );

        $deniedWordsStrategy = $this->createMock(TestDeniedWordsStrategy::class);
        $deniedWordsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $allowedDomainsStrategy = new TestAllowedDomainsStrategy();

        $validator = new CreateUserValidator(
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
    public function testAllowedEmailDomainCreate(): void
    {
        $user = new User(null, '1111111111', 'test@test.ru', '1');

        $repository = $this->createMock(TestUserRepository::class);
        $repository
            ->expects($this->any())
            ->method('find')
            ->willReturnOnConsecutiveCalls(
                null,
            );

        $deniedWordsStrategy = new TestDeniedWordsStrategy();

        $allowedDomainsStrategy = $this->createMock(TestAllowedDomainsStrategy::class);
        $allowedDomainsStrategy
            ->expects($this->any())
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(false);

        $validator = new CreateUserValidator(
            $repository,
            $deniedWordsStrategy,
            $allowedDomainsStrategy,
        );

        $this->expectExceptionObject(new EmailDomainIsNotAllowedException());
        $validator->validate($user);
    }
}