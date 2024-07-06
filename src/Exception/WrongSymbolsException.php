<?php

declare(strict_types=1);

namespace User\Exception;

class WrongSymbolsException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' wrong symbols in name';
        parent::__construct($message, $code, $previous);
    }
}