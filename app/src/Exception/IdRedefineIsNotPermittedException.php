<?php

declare(strict_types=1);

namespace User\Exception;

class IdRedefineIsNotPermittedException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' id redefine is not permitted';
        parent::__construct($message, $code, $previous);
    }
}