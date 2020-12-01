<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\ValueObject;

class Detection
{
    private string $label;
    private string $featureId;
    private Bbox $box;
    private Point $point;

    public function __construct(string $label, string $featureId, Bbox $box, Point $point)
    {
        $this->label = $label;
        $this->featureId = $featureId;
        $this->box = $box;
        $this->point = $point;
    }

    public static function fromArray(array $detectionResult): Detection
    {
        return new self(
            $detectionResult['label'],
            $detectionResult['featureId'],
            Bbox::fromArray($detectionResult['bbox']),
            Point::fromArray($detectionResult['point'])
        );
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getFeatureId(): string
    {
        return $this->featureId;
    }

    public function getBox(): Bbox
    {
        return $this->box;
    }

    public function getPoint(): Point
    {
        return $this->point;
    }
}
