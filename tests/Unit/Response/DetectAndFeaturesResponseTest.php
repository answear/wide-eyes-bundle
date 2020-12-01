<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Response\DetectAndFeaturesResponse;
use Answear\WideEyesBundle\ValueObject\Bbox;
use Answear\WideEyesBundle\ValueObject\Detection;
use Answear\WideEyesBundle\ValueObject\Point;
use PHPStan\Testing\TestCase;

class DetectAndFeaturesResponseTest extends TestCase
{
    /**
     * @test
     */
    public function correctlyReturnsDetections(): void
    {
        $responseData = [
            'detections' => [
                [
                    'label' => 'shorts',
                    'featureId' => 'aaaabbbb=',
                    'bbox' => [
                        'x1' => 10,
                        'y1' => 20,
                        'x2' => 30,
                        'y2' => 40,
                    ],
                    'point' => [
                        'x' => 20,
                        'y' => 30,
                    ],
                ],
                [
                    'label' => 't-shirt',
                    'featureId' => 'ccccdddd=',
                    'bbox' => [
                        'x1' => 50,
                        'y1' => 60,
                        'x2' => 70,
                        'y2' => 80,
                    ],
                    'point' => [
                        'x' => 60,
                        'y' => 70,
                    ],
                ],
            ],
        ];

        $response = DetectAndFeaturesResponse::fromArray($responseData);

        self::assertEquals(
            [
                new Detection(
                    'shorts',
                    'aaaabbbb=',
                    new Bbox(10, 20, 30, 40),
                    new Point(20, 30)
                ),
                new Detection(
                    't-shirt',
                    'ccccdddd=',
                    new Bbox(50, 60, 70, 80),
                    new Point(60, 70)
                ),
            ],
            $response->getDetections()
        );
    }

    /**
     * @test
     */
    public function malformedResponseWithoutDetections(): void
    {
        $responseData = [
            [
                'result' => 'some result',
            ],
        ];

        $this->expectException(MalformedResponse::class);
        DetectAndFeaturesResponse::fromArray($responseData);
    }
}
