<?php

declare(strict_types=1);

namespace User\Exception;

class DbFindException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' database find error';
        parent::__construct($message, $code, $previous);
    }
}