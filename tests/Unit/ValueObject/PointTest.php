<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\Point;
use PHPStan\Testing\TestCase;

class PointTest extends TestCase
{
    /**
     * @test
     */
    public function correctlyCreatesPointFromArray(): void
    {
        $point = [
            'x' => 20,
            'y' => 30,
        ];

        self::assertEquals(
            new Point(20, 30),
            Point::fromArray($point)
        );
    }

    /**
     * @test
     */
    public function throwsErrorWhenCreatingFromArrayNotFindProperties(): void
    {
        $point = [
            'x' => 20,
        ];

        $this->expectError();
        $this->expectErrorMessageMatches('#^Undefined index#');

        Point::fromArray($point);
    }
}
