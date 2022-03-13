<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\DeleteProject\DeleteProjectCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/project/{projectUuid}",
 *     requirements={"projectUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"DELETE"},
 * );
 *
 * @OpenApi\Annotations\Delete (
 *     path="/project/{projectUuid}",
 *     summary="Delete a Project",
 *     tags={"Projects"},
 * )
 */
class DeleteProjectController extends AbstractCommandController
{
    public function __invoke(string $projectUuid): JsonResponse
    {
        $this->dispatch(new DeleteProjectCommand($projectUuid));

        return new JsonResponse();
    }
}
