<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Application\Shared\Tenant\TenantProviderInterface;
use App\Application\Shared\Tenant\TenantSwitcherInterface;
use App\Infrastructure\Persistence\Doctrine\Connection\WrapperConnection;
use App\Infrastructure\Tenant\Repository\TenantConfigurationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestLoadTenantDBConnectionSubscriber implements EventSubscriberInterface
{
    private TenantProviderInterface $tenantProvider;
    private TenantSwitcherInterface $tenantSwitcher;

    public function __construct(
        TenantProviderInterface $tenantProvider,
        TenantSwitcherInterface $tenantSwitcher
    ) {
        $this->tenantProvider = $tenantProvider;
        $this->tenantSwitcher = $tenantSwitcher;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    public function onKernelRequest(RequestEvent $requestEvent)
    {
        $tenantHost = $requestEvent->getRequest()->getHost();

        $tenantConfiguration = $this->tenantProvider->getTenantConfiguration($tenantHost);
        if ($tenantConfiguration) {
            $this->tenantSwitcher->switch($tenantConfiguration);
        }
    }
}
