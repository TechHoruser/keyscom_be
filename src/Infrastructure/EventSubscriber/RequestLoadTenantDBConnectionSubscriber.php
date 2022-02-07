<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Application\Shared\Tenant\TenantProviderInterface;
use App\Application\Shared\Tenant\TenantSwitcherInterface;
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

    public static function getSubscribedEvents(): array
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
