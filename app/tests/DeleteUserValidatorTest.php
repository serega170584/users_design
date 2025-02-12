<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Test\Mock\TestUserRepositoryRepository;
use User\Entity\User;
use User\Exception\CreateMoreThenDeleteException;
use User\Validator\DeleteUserValidator;

class DeleteUserValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFailedDelete(): void
    {
        $interval = new \DateInterval('P2Y4DT6H8M');
        $interval->format('1 days');
        $created = (new \DateTimeImmutable())->add($interval);

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

        $validator = new DeleteUserValidator(
            $repository,
        );
        $this->expectExceptionObject(new CreateMoreThenDeleteException());
        $validator->validate($user, new \DateTimeImmutable());
    }

    /**
     * @throws \Exception
     */
    public function testSuccessedDelete(): void
    {
        $interval = new \DateInterval('P2Y4DT6H8M');
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

        $validator = new DeleteUserValidator(
            $repository,
        );
        $this->assertTrue($validator->validate($user, new \DateTimeImmutable()));
    }
}