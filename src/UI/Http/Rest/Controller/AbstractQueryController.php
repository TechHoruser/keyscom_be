<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Query\QueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractQueryController
{
    private QueryBusInterface $queryBus;
    protected Request $request;
    protected SerializerInterface $serializer;
    protected NormalizerInterface $normalizer;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer,
        QueryBusInterface $queryBus
    ) {
        /** @var Request request */
        $request = $requestStack->getCurrentRequest();
        if ($request instanceof Request) {
            $this->request = $request;
        }
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
        $this->queryBus = $queryBus;
    }

    protected function dispatch(QueryInterface $query)
    {
        return $this->queryBus->dispatch($query);
    }
}
