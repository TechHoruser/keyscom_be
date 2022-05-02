<?php

namespace App\Domain\User\Error;

use App\Domain\Shared\Errors\DomainError;

class EmailAlreadyInUseError extends DomainError
{
    public function __construct()
    {
        parent::__construct('Email already in use', 400);
    }
}
