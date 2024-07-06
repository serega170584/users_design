<?php

declare(strict_types=1);

namespace User;

use PHPUnit\Framework\TestCase;
use User\Dto\User;

class UseCaseTest extends TestCase
{
    public function testUseCase(): void
    {
        $user = new User(1,'1', '1', '1');
    }
}