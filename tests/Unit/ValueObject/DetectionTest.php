<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\Bbox;
use Answear\WideEyesBundle\ValueObject\Detection;
use Answear\WideEyesBundle\ValueObject\Point;
use PHPStan\Testing\TestCase;

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
                new Bbox(10, 20, 30, 40),
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
