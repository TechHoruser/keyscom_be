<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\GetProjects\GetProjectsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/project", methods={"GET"})
 *
 * @OpenApi\Annotations\Get (
 *     path="/project",
 *     summary="Get Projects",
 *     tags={"Projects"},
 *     @OpenApi\Annotations\Parameter (
 *         name="page",
 *         in="query",
 *         description="The number of page to show"
 *     )
 * )
 */
class GetProjectsController extends AbstractQueryController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new GetProjectsQuery(
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
