<?php

declare(strict_types=1);

namespace User\Exception;

class EmailIsNotValidException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' email is not valid';
        parent::__construct($message, $code, $previous);
    }
}