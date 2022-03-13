<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Projects;

use App\Application\UseCase\Project\CreateProject\CreateProjectCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/project", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/project",
 *     summary="Create a Project",
 *     tags={"Projects"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="userUuid", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="name", type="string", nullable=true),
 *       )
 *     ),
 * )
 */
class CreateProjectController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $result = $this->dispatch(new CreateProjectCommand(
            $this->request->request->get('uuid'),
            $this->request->request->get('name'),
            is_null($startDate = $this->request->request->get('startDate')) ?
                null :
                $this->dateTimeHelper->getDateTimeFromString($startDate)
            ,
            is_null($endDate = $this->request->request->get('endDate')) ?
                null :
                $this->dateTimeHelper->getDateTimeFromString($endDate)
            ,
            $this->request->request->get('clientUuid'),
        ));

        return new JsonResponse(
            $this->normalizer->normalize($result),
            Response::HTTP_CREATED,
        );
    }
}
