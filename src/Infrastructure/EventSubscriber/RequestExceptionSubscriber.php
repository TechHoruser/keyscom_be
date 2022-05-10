<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Application\Shared\Config\ParametersConfigInterface;
use App\Domain\Shared\Errors\DomainError;
use App\UI\Http\Rest\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ParametersConfigInterface $parametersConfig,
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

        if (!$this->isControllerRequest) {
            throw $error;
        }

        $payload = $this->getPayload($error);
        $event->setResponse(new JsonResponse($payload, $payload['code']));
    }

    private function getPayload(\Throwable $error): array {
        if ($error instanceof DomainError) {
            return [
                'code'    => $error->getCode(),
                'message' => $error->getMessage(),
            ];
        }

        if ($this->parametersConfig->get('app.env') === 'dev') {
            throw $error;
        }

        $this->logger->error($error);
        return [
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Internal Server Error',
        ];
    }
}
