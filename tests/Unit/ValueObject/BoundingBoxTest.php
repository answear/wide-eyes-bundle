<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\BoundingBox;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BoundingBoxTest extends TestCase
{
    #[Test]
    public function correctlyCreatesPointFromArray(): void
    {
        $bbox = [
            'x1' => 10,
            'y1' => 20,
            'x2' => 30,
            'y2' => 40,
        ];

        self::assertEquals(
            new BoundingBox(10, 20, 30, 40),
            BoundingBox::fromArray($bbox)
        );
    }

    #[Test]
    public function throwsErrorWhenCreatingFromArrayNotFindProperties(): void
    {
        $bbox = [
            'a' => 10,
            'y1' => 20,
            'x2' => 30,
            'y2' => 40,
        ];

        $this->expectExceptionMessage('Expected a value other than null.');

        BoundingBox::fromArray($bbox);
    }
}
