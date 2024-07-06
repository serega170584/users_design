<?php

declare(strict_types=1);

namespace Tests;

use User\Logger\LoggerInterface;

class TestLogger implements LoggerInterface
{

    function critical(string $message, array $context = []): void
    {
        printf($message, $context);
    }

    function error(string $message, array $context = []): void
    {
        printf($message, $context);
    }

    function info(string $message, array $context = []): void
    {
        printf($message, $context);
    }

    function warn(string $message, array $context = []): void
    {
        printf($message, $context);
    }
}