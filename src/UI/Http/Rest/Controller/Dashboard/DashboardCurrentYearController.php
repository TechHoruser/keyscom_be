<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Dashboard;

use App\Application\Shared\Enum\EntityType;
use App\Application\UseCase\Dashboard\DashboardCurrentYear\DashboardCurrentYearQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/dashboard-current-year", methods={"GET"});
 *
 * @OpenApi\Annotations\Get (
 *     path="/dashboard-current-year",
 *     summary="Get Dashboard Get New Entities By Day",
 *     tags={"Dashboard"},
 * )
 */
class DashboardCurrentYearController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new DashboardCurrentYearQuery(
            EntityType::from($this->request->query->get('type')),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
