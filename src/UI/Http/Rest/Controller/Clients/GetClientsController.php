<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\GetClients\GetClientsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/client", methods={"GET"});
 *
 * @OpenApi\Annotations\Get (
 *     path="/client",
 *     summary="Get Clients",
 *     tags={"Clients"},
 *     @OpenApi\Annotations\Parameter (
 *         name="page",
 *         in="query",
 *         description="The number of page to show"
 *     ),
 * )
 */
class GetClientsController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new GetClientsQuery(
            $this->getLoggedUser(),
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters'),
            $this->request->query->all('embeds'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
