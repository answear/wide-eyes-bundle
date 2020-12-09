<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\BoundingBox;
use Answear\WideEyesBundle\ValueObject\Detection;
use Answear\WideEyesBundle\ValueObject\Point;
use PHPUnit\Framework\TestCase;

class DetectionTest extends TestCase
{
    /**
     * @test
     */
    public function correctlyCreatesDetectionFromArray(): void
    {
        $detection = [
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
        ];

        self::assertEquals(
            new Detection(
                'shorts',
                'aaaabbbb=',
                'female',
                new BoundingBox(10, 20, 30, 40),
                new Point(20, 30)
            ),
            Detection::fromArray($detection)
        );
    }

    /**
     * @test
     */
    public function correctlyCreatesDetectionFromArrayWithoutGender(): void
    {
        $detection = [
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
        ];

        self::assertEquals(
            new Detection(
                'shorts',
                'aaaabbbb=',
                null,
                new BoundingBox(10, 20, 30, 40),
                new Point(20, 30)
            ),
            Detection::fromArray($detection)
        );
    }

    /**
     * @test
     */
    public function throwsErrorWhenCreatingFromArrayNotFindProperties(): void
    {
        $detection = [
            'label' => 'shorts',
            'feature' => 'aaaabbbb=',
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
        ];

        $this->expectError();
        $this->expectErrorMessageMatches('#^Undefined index#');

        Detection::fromArray($detection);
    }
}
