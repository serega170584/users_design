<?php

declare(strict_types=1);

namespace User\Exception;

class EmailIsNotUniqueException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' email is not unique';
        parent::__construct($message, $code, $previous);
    }
}