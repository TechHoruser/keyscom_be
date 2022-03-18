<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\User\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController
{
    protected Request $request;

    public function __construct(
        RequestStack $requestStack,
        protected SerializerInterface $serializer,
        protected NormalizerInterface $normalizer,
        protected Security $security,
    ) {
        $request = $requestStack->getCurrentRequest();
        if (! $request instanceof Request) return;
        $this->request = $request;
    }

    protected function generatePaginationPropertiesByQueryParams(): PaginationProperties
    {
        return new PaginationProperties(
            intval($this->request->query->get('page')),
            intval($this->request->query->get('page_size')),
            $this->request->query->get('sort_by'),
            $this->request->query->get('sort_order'),
        );
    }

    protected function getLoggedUser(): User
    {
        /** @var \App\Infrastructure\Security\User $securityUser */
        $securityUser = $this->security->getUser();

        return $securityUser->getDomainUser();
    }

    protected abstract function getBus();

    protected function dispatch($message)
    {
        return $this->getBus()->dispatch($message);
    }
}
