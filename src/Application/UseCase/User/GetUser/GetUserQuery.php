<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\GetUser;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\User\Entity\User;

class GetUserQuery implements QueryInterface
{
    public function __construct(
        readonly User $loggedUser,
        readonly string $uuid,
        readonly array $embeds,
    ) {}
}
