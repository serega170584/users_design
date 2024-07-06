<?php

declare(strict_types=1);

namespace User\Exception;

class OnlyNotEmptyIdIsPermittedForUpdateException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message .= ' only not empty id is permitted for update';
        parent::__construct($message, $code, $previous);
    }
}