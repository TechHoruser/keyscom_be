<?php

declare(strict_types=1);

namespace App\Infrastructure\Tenant;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Application\Shared\Tenant\TenantSwitcherInterface;
use App\Domain\Tenant\Entity\TenantInfrastructureConfiguration;
use App\Infrastructure\EventSubscriber\DoctrineSetTenantSubscriber;
use App\Infrastructure\Persistence\Doctrine\Connection\WrapperConnection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TenantSwitcher implements TenantSwitcherInterface
{
    private ManagerRegistry $managerRegistry;
    private ContainerInterface $container;
    private SecurityHelperInterface $securityHelper;

    /**
     * TenantSwitcher constructor.
     * @param ManagerRegistry $managerRegistry
     * @param ContainerInterface $container
     * @param SecurityHelperInterface $securityHelper
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ContainerInterface $container,
        SecurityHelperInterface $securityHelper
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->container = $container;
        $this->securityHelper = $securityHelper;
    }

    public function switch(TenantInfrastructureConfiguration $infrastructureConfiguration): void
    {
        if ($infrastructureConfiguration) {
            $this->container->set(
                DoctrineSetTenantSubscriber::CONTAINER_TENANT,
                $infrastructureConfiguration
            );

            /** @var WrapperConnection $tenantConnection */
            $tenantConnection = $this->managerRegistry->getConnection();
            $tenantConnection->switch(
                $infrastructureConfiguration->getUserDb(),
                $this->securityHelper->decryptString($infrastructureConfiguration->getPasswordDb())
            );
        }
    }
}
