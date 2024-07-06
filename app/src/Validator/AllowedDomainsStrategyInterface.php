<?php

declare(strict_types=1);

namespace User\Validator;

interface AllowedDomainsStrategyInterface
{
    function isValid(string $word): bool;
}