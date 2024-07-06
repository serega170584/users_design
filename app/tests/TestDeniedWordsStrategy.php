<?php

declare(strict_types=1);

namespace Tests;

use User\Validator\DeniedWordsStrategyInterface;

class TestDeniedWordsStrategy implements DeniedWordsStrategyInterface
{
    function isValid(string $word): bool
    {
        return true;
    }
}