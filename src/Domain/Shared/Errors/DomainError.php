<?php

declare(strict_types=1);

namespace App\Domain\Shared\Errors;

class DomainError extends \Exception
{
    public function __construct($message = '', $code = 400)
    {
        parent::__construct($message, $code);
    }
}
