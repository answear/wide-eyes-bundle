<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Service\SimilarClient;
use GuzzleHttp\Psr7\Response;

class SimilarClientTest extends AbstractClientTest
{
    private const UID = 'uid1';
    private const SECOND_UID = 'uid5';
    private const COUNTRY_CODE = 'code';

    private SimilarClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new SimilarClient($this->configProvider, $this->setupGuzzle());
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

        $this->client->getSimilar(self::UID, self::COUNTRY_CODE);
    }

    /**
     * @test
     */
    public function successfulManySimilarRequest(): void
    {
        $resultUids = ['uid2', 'uid3', 'uid4'];
        $resultUids2 = ['uid2', 'uid3'];

        $this->guzzleHandler->append(
            new Response(200, [], $this->prepareProperResponse($resultUids)),
            new Response(200, [], $this->prepareProperResponse($resultUids2))
        );

        $result = $this->client->getSimilarForMany([self::UID, self::SECOND_UID], self::COUNTRY_CODE);

        self::assertSame($resultUids, $result[self::UID]->getUids());
        self::assertSame($resultUids2, $result[self::SECOND_UID]->getUids());
        self::assertCount(2, $this->guzzleHistory);
    }

    /**
     * @test
     */
    public function successfulManySimilarRequestWithOne500(): void
    {
        $resultUids = ['uid2', 'uid3', 'uid4'];

        $this->guzzleHandler->append(
            new Response(200, [], $this->prepareProperResponse($resultUids)),
            new Response(500, [], '{}')
        );

        $result = $this->client->getSimilarForMany([self::UID, self::SECOND_UID], self::COUNTRY_CODE);

        self::assertSame($resultUids, $result[self::UID]->getUids());
        self::assertArrayNotHasKey(self::SECOND_UID, $result);
        self::assertCount(2, $this->guzzleHistory);
    }

    /**
     * @test
     */
    public function successfulManySimilarRequestWithOneErrorResult(): void
    {
        $resultUids = ['uid2', 'uid3', 'uid4'];

        $this->guzzleHandler->append(
            new Response(200, [], $this->prepareProperResponse($resultUids)),
            new Response(200, [], '{"success":true}')
        );

        $result = $this->client->getSimilarForMany([self::UID, self::SECOND_UID], self::COUNTRY_CODE);

        self::assertSame($resultUids, $result[self::UID]->getUids());
        self::assertSame([], $result[self::SECOND_UID]->getUids());
        self::assertCount(2, $this->guzzleHistory);
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
}
