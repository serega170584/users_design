<?php

declare(strict_types=1);

namespace User\Validator;

interface DeniedWordsStrategyInterface
{
    function isValid(string $word): bool;
}