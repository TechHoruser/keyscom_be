<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Domain\Shared\Errors\DomainError;
use App\UI\Http\Rest\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
       private bool $isControllerRequest = false,
    ){}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::EXCEPTION  => 'onKernelException',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AbstractController) {
            $this->isControllerRequest = true;
        }
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $error = $event->getThrowable()->getPrevious() ? $event->getThrowable()->getPrevious() : $event->getThrowable();

        if (!($error instanceof DomainError) || !$this->isControllerRequest) {
            throw $error;
        }

        $payload = [
            'code'    => $error->getCode(),
            'message' => $error->getMessage(),
        ];

        $event->setResponse(new JsonResponse($payload, $error->getCode()));
    }
}
