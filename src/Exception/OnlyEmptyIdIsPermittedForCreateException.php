<?php

declare(strict_types=1);

namespace User\Exception;

class OnlyEmptyIdIsPermittedForCreateException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' only empty id is permitted for create';
        parent::__construct($message, $code, $previous);
    }
}