<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\ValueObject;

use Answear\WideEyesBundle\ValueObject\Bbox;
use Monolog\Test\TestCase;

class BboxTest extends TestCase
{
    /**
     * @test
     */
    public function correctlyCreatesPointFromArray(): void
    {
        $bbox = [
            'x1' => 10,
            'y1' => 20,
            'x2' => 30,
            'y2' => 40,
        ];

        self::assertEquals(
            new Bbox(10, 20, 30, 40),
            Bbox::fromArray($bbox)
        );
    }

    /**
     * @test
     */
    public function throwsErrorWhenCreatingFromArrayNotFindProperties(): void
    {
        $bbox = [
            'a' => 10,
            'y1' => 20,
            'x2' => 30,
            'y2' => 40,
        ];

        $this->expectError();
        $this->expectErrorMessageMatches('#^Undefined index#');

        Bbox::fromArray($bbox);
    }
}
