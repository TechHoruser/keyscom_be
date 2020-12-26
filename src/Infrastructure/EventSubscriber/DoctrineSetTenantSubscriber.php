<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Domain\Tenant\CertainTenant\TenantEntityTrait;
use App\Infrastructure\Tenant\Entity\TenantConfiguration;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineSetTenantSubscriber implements EventSubscriber
{
    public const CONTAINER_TENANT = 'tenant';

    private ContainerInterface $container;

    /**
     * DoctrineSetTenantSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof TenantEntityTrait && $this->container->has(self::CONTAINER_TENANT)) {
            /** @var TenantConfiguration $tenant */
            $tenant = $this->container->get(self::CONTAINER_TENANT);
            $entity->setTenantName($tenant->getUserDb());
        }
    }
}
