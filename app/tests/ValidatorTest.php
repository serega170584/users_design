<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use Test\Mock\TestAllowedDomainsStrategy;
use Test\Mock\TestDeniedWordsStrategy;
use Test\Mock\TestUserRepository;
use User\Dto\User;
use User\Exception\OnlyEmptyIdIsPermittedForCreateException;
use User\Validator\CreateUserValidator;

class ValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreate(): void
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
}