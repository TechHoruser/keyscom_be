<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\GetProjects\GetProjectsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/projects", methods={"GET"})
 *
 * @OA\Get (
 *     path="/projects",
 *     summary="Get Projects",
 *     tags={"projects"},
 *     @OA\Parameter (
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
            intval($this->request->query->get('page', 0)),
            intval($this->request->query->get('page_size', 0)),
            $this->request->query->get('sort_by'),
            $this->request->query->get('sort_order'),
            $this->request->query->all('filters')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
