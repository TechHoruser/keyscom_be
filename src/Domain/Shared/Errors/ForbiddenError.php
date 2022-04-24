<?php

namespace App\Domain\Shared\Errors;

class ForbiddenError extends DomainError
{
    public function __construct()
    {
        parent::__construct('Forbidden', 403);
    }
}
