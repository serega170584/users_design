<?php

declare(strict_types=1);

namespace User\Logger;

interface LoggerInterface
{
    function critical(string $message, array $context = []): void;
    function error(string $message, array $context = []): void;
    function info(string $message, array $context = []): void;
    function warn(string $message, array $context = []): void;
}