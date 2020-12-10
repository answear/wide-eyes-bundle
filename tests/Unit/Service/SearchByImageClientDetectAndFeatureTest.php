<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Service\SearchByImageClient;
use GuzzleHttp\Psr7\Response;

class SearchByImageClientDetectAndFeatureTest extends AbstractClientTest
{
    private const IMAGE_PATH = 'path';

    private SearchByImageClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new SearchByImageClient($this->configProvider, $this->setupGuzzle());
    }

    /**
     * @test
     */
    public function successfulDetectAndFeatures(): void
    {
        $x1 = 1.0;
        $x = 2.0;
        $box = [
            'x1' => $x1,
            'x2' => 2.0,
            'y1' => 3.0,
            'y2' => 4.0,
        ];
        $point = [
            'x' => $x,
            'y' => 3.0,
        ];
        $label = 'label1';
        $featureId = 'featureId1';

        $this->guzzleHandler->append(new Response(200, [], $this->prepareProperResponse($label, $featureId, $box, $point)));

        $result = $this->client->detectAndFeatures(self::IMAGE_PATH);

        $detections = $result->getDetections();
        self::assertCount(1, $detections);
        self::assertSame($label, $detections[0]->getLabel());
        self::assertSame($featureId, $detections[0]->getFeatureId());
        self::assertSame($x1, $detections[0]->getBox()->getX1());
        self::assertSame($x, $detections[0]->getPoint()->getX());
        self::assertCount(1, $this->guzzleHistory);
    }

    /**
     * @test
     */
    public function responseWithWrongPropertiesInDetections(): void
    {
        $this->guzzleHandler->append(new Response(200, [], $this->prepareNotProperResponse()));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Undefined index: featureId');

        $this->client->detectAndFeatures(self::IMAGE_PATH);
    }

    /**
     * @test
     */
    public function responseWithoutResult(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '{"success":true}'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected the key "results" to exist.');

        $this->client->detectAndFeatures(self::IMAGE_PATH);
    }

    /**
     * @test
     */
    public function responseWithoutArray(): void
    {
        $this->guzzleHandler->append(new Response(200, [], '"result":[]'));

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Expected an array. Got: NULL');

        $this->client->detectAndFeatures(self::IMAGE_PATH);
    }

    /**
     * @test
     */
    public function serviceUnavailable(): void
    {
        $this->guzzleHandler->append(new Response(500, [], '{}'));

        $this->expectException(ServiceUnavailable::class);

        $this->client->detectAndFeatures(self::IMAGE_PATH);
    }

    private function prepareProperResponse(string $label, string $featureId, array $box, array $point): string
    {
        $result = [
            'results' => [
                'detections' => [
                    [
                        'label' => $label,
                        'featureId' => $featureId,
                        'bbox' => $box,
                        'point' => $point,
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
                    'detections' => [
                        [
                            'label' => 'label',
                            'feature' => 'feature',
                            'bbox' => [],
                            'point' => [],
                        ],
                    ],
                ],
            ]
        );
    }
}
