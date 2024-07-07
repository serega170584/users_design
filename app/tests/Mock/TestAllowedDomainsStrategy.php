<?php

declare(strict_types=1);

namespace Test\Mock;

use User\Validator\AllowedDomainsStrategyInterface;

class TestAllowedDomainsStrategy implements AllowedDomainsStrategyInterface
{
    function isValid(string $word): bool
    {
        return true;
    }
}