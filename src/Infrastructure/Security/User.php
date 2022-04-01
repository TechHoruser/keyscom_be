<?php

namespace App\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends \App\Domain\User\Entity\User
    implements UserInterface, JWTUserInterface, PasswordAuthenticatedUserInterface
{
    public static function createFromUser(\App\Domain\User\Entity\User $user): static
    {
        return new static(
            $user->getUuid(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getPubKey(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPermissions(),
        );
    }

    public static function createFromPayload($username, array $payload): static
    {
        return new static(
            $payload['uuid'],
            $payload['email'],
            null,
            null,
            $payload['firstName'],
            $payload['lastName'],
        );
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials() {}

    public function getUserIdentifier(): string
    {
        return $this->getUuid();
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getDomainUser(): \App\Domain\User\Entity\User
    {
        return new parent(
            $this->getUuid(),
            $this->getEmail(),
            $this->getPassword(),
            $this->getPubKey(),
            $this->getFirstName(),
            $this->getLastName(),
            $this->getPermissions(),
        );
    }

}
