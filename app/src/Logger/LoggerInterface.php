<?php

declare(strict_types=1);

namespace User\Logger;

interface LoggerInterface
{
    function critical(string $message, array $context = []);
    function error(string $message, array $context = []);
    function info(string $message, array $context = []);
    function warn(string $message, array $context = []);
}