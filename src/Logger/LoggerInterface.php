<?php

declare(strict_types=1);

namespace User\Logger;

interface LoggerInterface
{
    function error(string $message);
    function info(string $message);
    function warn(string $message);
}