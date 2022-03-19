<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\GetClient\GetClientQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/client/{clientUuid}",
 *     requirements={"clientUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"GET"}
 * );
 *
 * @OpenApi\Annotations\Get (
 *     path="/client/{clientUuid}",
 *     summary="Get a Client",
 *     tags={"Clients"},
 *     @OpenApi\Annotations\Parameter (
 *         name="embeds",
 *         in="query",
 *         description="array of embeds"
 *     ),
 * )
 */
class GetClientController extends AbstractQueryController
{
    public function __invoke(string $clientUuid): JsonResponse
    {
        $results = $this->dispatch(new GetClientQuery(
            $this->getLoggedUser(),
            $clientUuid,
            $this->request->query->all('embeds'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
