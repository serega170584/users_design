<?php

declare(strict_types=1);

namespace User\Exception;

class CreateMoreThenDeleteException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' create can not be more than delete';
        parent::__construct($message, $code, $previous);
    }
}