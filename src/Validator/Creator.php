<?php

declare(strict_types=1);

namespace User\Validator;

use User\Dto\User;

class Creator
{
    /**
     * @throws \Exception
     */
    public function validate(User $user): voif
    {
        if ($user->name === '') {
            throw new \Exception('123');
        }
    }
}