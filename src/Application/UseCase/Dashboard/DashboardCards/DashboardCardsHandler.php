<?php

declare(strict_types=1);

namespace App\Application\UseCase\Dashboard\DashboardCards;

use App\Application\Shared\Dto\Dashboard\DashboardCardsDto;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class DashboardCardsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly MachineRepositoryInterface $machineRepository,
    ) {}

    public function __invoke(DashboardCardsQuery $dashboardCardsQuery): DashboardCardsDto
    {
        return new DashboardCardsDto(
            $this->userRepository->countAll(),
            $this->clientRepository->countAll(),
            $this->projectRepository->countAll(),
            $this->machineRepository->countAll(),
        );
    }
}
