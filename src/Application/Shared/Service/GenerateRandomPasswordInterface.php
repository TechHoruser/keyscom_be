<?php

declare(strict_types=1);

namespace App\Application\Shared\Service;

use App\Application\Shared\Dto\User\PasswordDto;
use App\Domain\User\Entity\User;

interface GenerateRandomPasswordInterface
{
    public function __invoke(User $user): PasswordDto;
}
