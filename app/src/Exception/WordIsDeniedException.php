<?php

declare(strict_types=1);

namespace User\Exception;

class WordIsDeniedException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' word is denied';
        parent::__construct($message, $code, $previous);
    }
}