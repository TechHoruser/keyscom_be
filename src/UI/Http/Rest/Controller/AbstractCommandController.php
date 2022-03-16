<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Application\Shared\Helper\DateTimeHelperInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractCommandController extends AbstractController
{
    public function __construct(
        protected DateTimeHelperInterface $dateTimeHelper,
        private CommandBusInterface $commandBus,
        RequestStack $requestStack,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer,
        Security $security,
    ) {
        parent::__construct($requestStack, $serializer, $normalizer, $security);

        if ('json' === $this->request->getContentType() && $this->request->getContent()) {
            $data = json_decode(
                (string) $this->request->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (is_array($data)) {
                $this->request->request->replace($data);
            }
        }
    }

    protected function dispatch(CommandInterface $command)
    {
        return $this->commandBus->dispatch($command);
    }
}
