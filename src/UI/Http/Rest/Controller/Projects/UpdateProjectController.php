<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\UpdateProject\UpdateProjectCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/project/{projectUuid}",
 *     requirements={"projectUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"PUT"},
 * );
 *
 * @OpenApi\Annotations\Put (
 *     path="/project/{projectUuid}",
 *     summary="Update a Project",
 *     tags={"Projects"},
 * )
 */
class UpdateProjectController extends AbstractCommandController
{
    public function __invoke(string $projectUuid): JsonResponse
    {
        $this->dispatch(new UpdateProjectCommand(
            $this->getLoggedUser(),
            $projectUuid,
            $this->request->request->get('name'),
        ));

        return new JsonResponse();
    }
}
