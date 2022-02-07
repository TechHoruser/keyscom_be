<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\GetClients\GetClientsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new GetClientsQuery(
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
