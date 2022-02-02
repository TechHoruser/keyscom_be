<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\GetUsers\GetUsersQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/user", methods={"GET"})
 *
 * @OA\Get (
 *     path="/user",
 *     summary="Get Users",
 *     tags={"Users"},
 *     @OA\Parameter (
 *         name="page",
 *         in="query",
 *         description="The number of page to show"
 *     )
 * )
 */
class GetUsersController extends AbstractQueryController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new GetUsersQuery(
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
