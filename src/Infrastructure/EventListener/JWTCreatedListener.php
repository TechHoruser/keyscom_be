<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * Adds additional data to the generated JWT
     *
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        $payload = array_merge(
            $event->getData(),
            [
                'uuid' => $user->getUserIdentifier(),
            ]
        );

        $event->setData($payload);
    }
}
