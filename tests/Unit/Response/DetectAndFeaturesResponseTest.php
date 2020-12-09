<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Response\DetectAndFeaturesResponse;
use Answear\WideEyesBundle\ValueObject\BoundingBox;
use Answear\WideEyesBundle\ValueObject\Detection;
use Answear\WideEyesBundle\ValueObject\Point;
use PHPUnit\Framework\TestCase;

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
                    'gender' => 'female',
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
                    'female',
                    new BoundingBox(10, 20, 30, 40),
                    new Point(20, 30)
                ),
                new Detection(
                    't-shirt',
                    'ccccdddd=',
                    null,
                    new BoundingBox(50, 60, 70, 80),
                    new Point(60, 70)
                ),
            ],
            $response->getDetections()
        );
    }

    /**
     * @test
     */
    public function malformedResponseWithNotCompleteDetections(): void
    {
        $responseData = [
            'detections' => [
                [
                    'label' => 'shorts',
                    'featureId' => 'aaaabbbb=',
                    'gender' => 'female',
                    'bbox' => [
                        'x1' => 10,
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

        $this->expectException(MalformedResponse::class);
        $this->expectExceptionMessage('Undefined index: y1');
        DetectAndFeaturesResponse::fromArray($responseData);
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
