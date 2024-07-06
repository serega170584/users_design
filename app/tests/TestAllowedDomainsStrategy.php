<?php

declare(strict_types=1);

namespace tests;

use User\Validator\AllowedDomainsStrategyInterface;

class TestAllowedDomainsStrategy implements AllowedDomainsStrategyInterface
{
    function isValid(string $word): bool
    {
        return true;
    }
}