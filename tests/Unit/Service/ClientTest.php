<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Service\Client;
use Answear\WideEyesBundle\Service\ConfigProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @see http://docs.guzzlephp.org/en/stable/testing.html#history-middleware
     */
    private $guzzleHistory;
    private MockHandler $guzzleHandler;

    private const API_URL = 'https://wideeyes-fake-api.test/';
    private const API_KEY = 'api-key';
    private const UID = 'uid1';
    private const COUNTRY_CODE = 'code';

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $configProvider = new ConfigProvider(
            self::API_URL,
            self::API_KEY,
            1.0,
            1.0
        );

        $this->client = new Client($configProvider, $this->setupGuzzle());
    }

    /**
     * @test
     */
    public function successfulSimilarRequest(): void
    {
        $resultUids = ['uid2', 'uid3', 'uid4'];

        $this->guzzleHandler->append(new Response(200, [], $this->prepareProperResponse($resultUids)));

        $result = $this->client->getSimilar(self::UID, self::COUNTRY_CODE);

        self::assertSame($resultUids, $result->getUids());
        self::assertCount(1, $this->guzzleHistory);
    }

    /**
     * @test
     */
    public function successfulEmptySimilarRequest(): void
    {
        $resultUids = [];

        $this->guzzleHandler->append(new Response(200, [], $this->prepareProperResponse($resultUids)));

        $result = $this->client->getSimilar(self::UID, self::COUNTRY_CODE);

        self::assertSame($resultUids, $result->getUids());
        self::assertCount(1, $this->guzzleHistory);
    }

    /**
     * @test
     */
    public function responseWithoutUids(): void
    {
        $this->guzzleHandler->append(new Response(200, [], $this->prepareResponseWithoutUids()));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected the key "uid" to exist.');

        $this->client->getSimilar(self::UID, self::COUNTRY_CODE);
    }

    /**
     * @test
     */
    public function responseWithoutResult(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '{"success":true}'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected the key "results" to exist.');

        $this->client->getSimilar(self::UID, self::COUNTRY_CODE);
    }

    /**
     * @test
     */
    public function responseWithoutArray(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '"result":[]'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected an array. Got: NULL');

        $this->client->getSimilar(self::UID, self::COUNTRY_CODE);
    }

    /**
     * @test
     */
    public function serviceUnavailable(): void
    {
        $this->guzzleHandler->append(new Response(500, [], '{}'));

        $this->expectException(ServiceUnavailable::class);
        $this->expectExceptionMessage('Server error: `POST v4/SearchById` resulted in a `500 Internal Server Error');

        $this->client->getSimilar(self::UID, self::COUNTRY_CODE);
    }

    private function prepareProperResponse(array $resultUids): string
    {
        $result = [
            'results' => [],
        ];

        foreach ($resultUids as $uid) {
            $result['results'][] = ['uid' => $uid];
        }

        return \json_encode($result);
    }

    private function prepareResponseWithoutUids(): string
    {
        return \json_encode(
            [
                'results' => [
                    [
                        'item1',
                    ],
                    [
                        'item2',
                    ],
                    [
                        'item3',
                    ],
                ],
            ]
        );
    }

    private function setupGuzzle(): ClientInterface
    {
        $this->guzzleHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->guzzleHandler);

        $this->guzzleHistory = [];
        $history = Middleware::history($this->guzzleHistory);
        $handlerStack->push($history);

        return new \GuzzleHttp\Client(['handler' => $handlerStack]);
    }
}
