<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\ValueObject;

class Point
{
    private float $x;
    private float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function fromArray(array $pointResponse): Point
    {
        return new self(
            (float) $pointResponse['x'],
            (float) $pointResponse['y']
        );
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}
