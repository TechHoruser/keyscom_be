<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\GetClients\GetClientsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Symfony\Component\Routing\Annotation\Route("/clients", methods={"GET"})
 *
 * @OpenApi\Annotations\Get(
 *     path="/clients"
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
            intval($this->request->query->get('page', 0)),
            intval($this->request->query->get('page_size', 0)),
            $this->request->query->get('sort_by'),
            $this->request->query->get('sort_order'),
            $this->request->query->all('filters')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
