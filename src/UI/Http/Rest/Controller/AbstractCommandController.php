<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractCommandController
{
    private CommandBusInterface $commandBus;
    protected Request $request;
    protected SerializerInterface $serializer;
    protected NormalizerInterface $normalizer;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer,
        CommandBusInterface $commandBus
    ) {
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
        $this->commandBus = $commandBus;
        /** @var Request request */
        $request = $requestStack->getCurrentRequest();
        if ($request instanceof Request) {
            $this->request = $request;
            if ('json' === $request->getContentType() && $request->getContent()) {
                try {
                    $data = json_decode(
                        (string) $request->getContent(),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );

                    if (is_array($data)) {
                        $request->request->replace($data);
                    }
                } catch (\JsonException $exception) {
                    return new JsonResponse(
                        ['message' => $exception->getMessage()],
                        Response::HTTP_BAD_REQUEST
                    );
                }
            }
        }
    }

    protected function dispatch(CommandInterface $command)
    {
        return $this->commandBus->dispatch($command);
    }
}
