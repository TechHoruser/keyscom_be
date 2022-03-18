<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller;

use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionType;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractControllerIntegrationTest extends WebTestCase
{
    protected const GET = 'GET';
    protected const POST = 'POST';
    protected const PUT = 'PUT';
    protected const DELETE = 'DELETE';
    protected const REQUEST_FORMAT = 'json';
    protected const LOGIN_CHECK_PATH = '/login_check';
    protected KernelBrowser $client;
    /** @var FakerFactoryInterface */
    protected $fakerFactory;
    /** @var EntityManagerInterface */
    protected $_em;
    /** @var NormalizerInterface */
    protected $normalizer;
    /** @var SerializerInterface */
    protected $serializer;
    protected string $token;

    protected function setUp(): void
    {
        $this->client = $this->createClient();

        $container = static::getContainer();

        $this->fakerFactory = $container->get(FakerFactoryInterface::class);
        $this->_em = $container->get(EntityManagerInterface::class);
        $this->normalizer = $container->get(NormalizerInterface::class);
        $this->serializer = $container->get(SerializerInterface::class);

        $schemaTool = new SchemaTool($this->_em);
        $metadata = $this->_em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    private function getAuthorizationToken(): string
    {
        $this->token ?? $this->setAuthorizationToken();
        return $this->token;
    }

    /**
     * @param User|null $user
     * @param Permission[]|null $permissions
     */
    protected function setAuthorizationToken(?User $user = null, ?array $permissions = null): void
    {
        if (is_null($user)) {
            $user = $this->fakerFactory->newUser();
            $user->setEmail('fullAccess@user.com');
        }
        $this->_em->persist($user);
        $this->_em->flush();

        if (is_null($permissions)) {
            $permissions = [new Permission(
                null,
                null,
                $user,
                PermissionType::ADMIN,
                null,
                null,
                null,
            )];
        }

        foreach ($permissions as $permission) {
            $this->_em->persist($permission);
            $this->_em->flush();
        }

        $this->client->request(
            self::POST,
            self::LOGIN_CHECK_PATH,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->serializer->serialize([
                'username' => $user->getEmail(),
                'password' => $this->fakerFactory->getUserPassword(),
            ], self::REQUEST_FORMAT),
        );

        $this->token = json_decode($this->client->getResponse()->getContent(), true)['token'];
    }

    protected function jsonEncodeAsHttpResponse($data): string
    {
        return json_encode($data, JsonResponse::DEFAULT_ENCODING_OPTIONS);
    }

    protected function sendRequest(
        string $method,
        string $path,
        mixed $queryParams = [],
        mixed $body = [],
    ): Response
    {
        $this->client->request(
            $method,
            $path,
            $queryParams,
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getAuthorizationToken(),
            ],
            $this->serializer->serialize($body, self::REQUEST_FORMAT),
        );
        return $this->client->getResponse();
    }
}
