<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\Point;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    #[Test]
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

    #[Test]
    public function throwsErrorWhenCreatingFromArrayNotFindProperties(): void
    {
        $point = [
            'x' => 20,
        ];

        $this->expectExceptionMessage('Expected a value other than null.');

        Point::fromArray($point);
    }
}
