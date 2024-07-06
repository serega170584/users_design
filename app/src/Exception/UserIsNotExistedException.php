<?php

declare(strict_types=1);

namespace User\Exception;

class UserIsNotExistedException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' user is not existed';
        parent::__construct($message, $code, $previous);
    }
}