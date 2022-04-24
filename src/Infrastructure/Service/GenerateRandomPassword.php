<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Shared\Dto\User\PasswordDto;
use App\Application\Shared\Service\GenerateRandomPasswordInterface;
use App\Domain\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GenerateRandomPassword implements GenerateRandomPasswordInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(User $user): PasswordDto
    {
        $password = bin2hex(openssl_random_pseudo_bytes(4));

        return new PasswordDto(
            $password,
            $this->passwordHasher->hashPassword(
                \App\Infrastructure\Security\User::createFromDomainUser($user),
                $password
            ),
        );
    }
}
