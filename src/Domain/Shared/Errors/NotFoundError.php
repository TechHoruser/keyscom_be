<?php

namespace App\Domain\Shared\Errors;

class NotFoundError extends DomainError
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Not Found', 404);
    }
}
