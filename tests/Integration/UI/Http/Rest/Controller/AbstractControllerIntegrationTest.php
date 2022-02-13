<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller;

use App\Tests\Resources\Factory\FakerFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractControllerIntegrationTest extends WebTestCase
{
    protected const GET = 'GET';
    protected const POST = 'POST';
    protected const PUT = 'PUT';
    protected const DELETE = 'DELETE';
    protected KernelBrowser $client;
    /** @var FakerFactoryInterface */
    protected $fakerFactory;
    /** @var EntityManagerInterface */
    protected $_em;

    protected function setUp(): void
    {
        $this->client = $this->createClient();

        $container = static::getContainer();

        $this->fakerFactory = $container->get(FakerFactoryInterface::class);
        $this->_em = $container->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($this->_em);
        $metadata = $this->_em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function jsonEncodeAsHttpResponse($data): string
    {
        return json_encode($data, JsonResponse::DEFAULT_ENCODING_OPTIONS);
    }
}
