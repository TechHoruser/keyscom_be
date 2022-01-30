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
        $this->client = static::createClient();
        self::bootKernel();
    }

    protected function login(): void
    {
        $loginPath = sprintf('https://%s/oauth/token', getenv('AUTH0_DOMAIN'));
        $loginBody = [
            "client_id" => getenv('AUTH0_CLIENT_ID'),
            "client_secret" => getenv('AUTH0_CLIENT_SECRET'),
            "audience" => getenv('AUTH0_API_IDENTIFIER'),
            "grant_type" => "client_credentials"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $loginPath,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => self::POST,
            CURLOPT_POSTFIELDS => json_encode($loginBody),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \HttpException("cURL Error #:" . $err);
        }

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $response['access_token']));
    }

    protected function jsonEncodeAsHttpResponse($data): string
    {
        return json_encode($data, JsonResponse::DEFAULT_ENCODING_OPTIONS);
    }
}
