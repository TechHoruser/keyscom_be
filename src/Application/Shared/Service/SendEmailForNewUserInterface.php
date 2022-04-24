<?php

declare(strict_types=1);

namespace App\Application\Shared\Service;

use App\Domain\User\Entity\User;

interface SendEmailForNewUserInterface
{
    public function __invoke(User $user, string $password): void;
}
