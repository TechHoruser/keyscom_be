<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto\User;

class PasswordDto
{
    public function __construct(
        readonly string $decrypted,
        readonly string $encrypted,
    ) {}
}
