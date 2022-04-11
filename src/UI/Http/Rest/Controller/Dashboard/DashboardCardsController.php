<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Dashboard;

use App\Application\UseCase\Dashboard\DashboardCards\DashboardCardsQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/dashboard-card", methods={"GET"});
 *
 * @OpenApi\Annotations\Get (
 *     path="/dashboard-card",
 *     summary="Get Dashboard Cards",
 *     tags={"Dashboard"},
 * )
 */
class DashboardCardsController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new DashboardCardsQuery());

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
