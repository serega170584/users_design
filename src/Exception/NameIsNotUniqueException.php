<?php

declare(strict_types=1);

namespace User\Exception;

class NameIsNotUniqueException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' name is not unique';
        parent::__construct($message, $code, $previous);
    }
}