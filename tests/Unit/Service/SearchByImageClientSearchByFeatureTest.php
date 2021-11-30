<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Service\SearchByImageClient;
use GuzzleHttp\Psr7\Response;

class SearchByImageClientSearchByFeatureTest extends AbstractClientTest
{
    private const FEATURE_ID = 'featureid==';
    private const LABEL = 'label';
    private const GENDER = 'male';
    private const COUNTRY = 'pl';

    private SearchByImageClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new SearchByImageClient($this->configProvider, $this->setupGuzzle());
    }

    /**
     * @dataProvider dataProvider
     * @test
     */
    public function successfulSearchByFeature(
        string $featureId,
        string $label,
        ?string $gender = null,
        ?string $country = null
    ): void {
        $uid1 = 'uid1';
        $uid2 = 'uid2';
        $uid3 = 'uid3';

        $this->guzzleHandler->append(new Response(200, [], $this->prepareProperResponse($uid1, $uid2, $uid3)));

        $result = $this->client->searchByFeature($featureId, $label, $gender, $country);

        self::assertSame([$uid1, $uid2, $uid3], $result->getUids());
        self::assertCount(1, $this->guzzleHistory);
    }

    public function dataProvider(): iterable
    {
        yield [
            self::FEATURE_ID,
            self::LABEL,
            self::GENDER,
            self::COUNTRY,
        ];

        yield [
            self::FEATURE_ID,
            self::LABEL,
        ];

        yield [
            self::FEATURE_ID,
            self::LABEL,
            self::GENDER,
        ];

        yield [
            self::FEATURE_ID,
            self::LABEL,
            null,
            self::COUNTRY,
        ];
    }

    /**
     * @test
     */
    public function responseWithoutProducts(): void
    {
        $this->guzzleHandler->append(new Response(200, [], $this->prepareNotProperResponse()));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Empty result');

        $this->client->searchByFeature(self::FEATURE_ID, self::LABEL);
    }

    /**
     * @test
     */
    public function responseWithoutResult(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '{"success":true}'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected the key "results" to exist.');

        $this->client->searchByFeature(self::FEATURE_ID, self::LABEL);
    }

    /**
     * @test
     */
    public function responseWithoutArray(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '"result":[]'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected an array. Got: NULL');

        $this->client->searchByFeature(self::FEATURE_ID, self::LABEL);
    }

    /**
     * @test
     */
    public function serviceUnavailable(): void
    {
        $this->guzzleHandler->append(new Response(500, [], '{}'));

        $this->expectException(ServiceUnavailable::class);

        $this->client->searchByFeature(self::FEATURE_ID, self::LABEL);
    }

    private function prepareProperResponse(string $uid1, string $uid2, string $uid3): string
    {
        $result = [
            'results' => [
                [
                    'products' => [
                        [
                            'uid' => $uid1,
                            'otherData' => 'some data',
                        ],
                        [
                            'uid' => $uid2,
                        ],
                        [
                            'uid' => $uid3,
                            'category' => 'category',
                        ],
                    ],
                ],
            ],
        ];

        return \json_encode($result);
    }

    private function prepareNotProperResponse(): string
    {
        return \json_encode(
            [
                'results' => [
                    [
                        'items' => [
                            [
                                'uid' => 'uid',
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
