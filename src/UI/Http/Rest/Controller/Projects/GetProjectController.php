<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\GetProject\GetProjectQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/project/{projectUuid}",
 *     requirements={"projectUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"GET"}
 * );
 *
 * @OpenApi\Annotations\Get (
 *     path="/project/{projectUuid}",
 *     summary="Get a Project",
 *     tags={"Projects"},
 *     @OpenApi\Annotations\Parameter (
 *         name="embeds",
 *         in="query",
 *         description="array of embeds"
 *     ),
 * )
 */
class GetProjectController extends AbstractQueryController
{
    public function __invoke(string $projectUuid): JsonResponse
    {
        $results = $this->dispatch(new GetProjectQuery(
            $projectUuid,
            $this->request->query->all('embeds'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
