<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\ValueObject;

use Webmozart\Assert\Assert;

class BoundingBox
{
    private float $x1;
    private float $y1;
    private float $x2;
    private float $y2;

    public function __construct(float $x1, float $y1, float $x2, float $y2)
    {
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->x2 = $x2;
        $this->y2 = $y2;
    }

    public static function fromArray(array $bboxResponse): BoundingBox
    {
        Assert::notNull($bboxResponse['x1']);
        Assert::notNull($bboxResponse['y1']);
        Assert::notNull($bboxResponse['x2']);
        Assert::notNull($bboxResponse['y2']);

        return new self(
            (float) $bboxResponse['x1'],
            (float) $bboxResponse['y1'],
            (float) $bboxResponse['x2'],
            (float) $bboxResponse['y2']
        );
    }

    public function getX1(): float
    {
        return $this->x1;
    }

    public function getY1(): float
    {
        return $this->y1;
    }

    public function getX2(): float
    {
        return $this->x2;
    }

    public function getY2(): float
    {
        return $this->y2;
    }
}
