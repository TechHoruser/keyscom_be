<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Shared\Service\SendEmailForNewUserInterface;
use App\Domain\User\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendEmailForNewUser implements SendEmailForNewUserInterface
{
    public function __construct(
        private MailerInterface $mailer,
    ) {}

    public function __invoke(User $user, string $password): void
    {
        $this->mailer->send((new Email())
            ->from('hello@keyscom.com')
            ->to($user->getEmail())
            ->subject('Hello to Keyscom!')
            ->text(sprintf(
                "
                Hello %s %s!
                Now you can access to http://localhost:4200 with the following credentials:
                 - username: %s
                 - password: %s
                ",
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                $password,
            )));
    }
}
