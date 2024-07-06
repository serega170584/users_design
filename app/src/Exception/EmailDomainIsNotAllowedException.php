<?php

declare(strict_types=1);

namespace User\Exception;

class EmailDomainIsNotAllowedException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' email domain is not allowed';
        parent::__construct($message, $code, $previous);
    }
}