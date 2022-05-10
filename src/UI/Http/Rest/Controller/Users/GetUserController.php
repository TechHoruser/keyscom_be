<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\GetUser\GetUserQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/user/{userUuid}",
 *     requirements={"userUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"GET"}
 * );
 *
 * @OpenApi\Annotations\Get (
 *     path="/user/{userUuid}",
 *     summary="Get a User",
 *     tags={"Users"},
 *     @OpenApi\Annotations\Parameter (
 *         name="embeds",
 *         in="query",
 *         description="array of embeds"
 *     ),
 * )
 */
class GetUserController extends AbstractQueryController
{
    public function __invoke(string $userUuid): JsonResponse
    {
        $results = $this->dispatch(new GetUserQuery(
            $this->getLoggedUser(),
            $userUuid,
            $this->request->query->all('embeds'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
