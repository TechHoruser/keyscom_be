<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller;

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

    protected function setUp(): void
    {
        $this->client = $this->createClient();
    }

    protected function jsonEncodeAsHttpResponse($data): string
    {
        return json_encode($data, JsonResponse::DEFAULT_ENCODING_OPTIONS);
    }
}
