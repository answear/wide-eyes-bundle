<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\ValueObject;

use Webmozart\Assert\Assert;

class Detection
{
    private string $label;
    private string $featureId;
    private ?string $gender;
    private BoundingBox $box;
    private Point $point;

    public function __construct(string $label, string $featureId, ?string $gender, BoundingBox $box, Point $point)
    {
        $this->label = $label;
        $this->featureId = $featureId;
        $this->box = $box;
        $this->gender = $gender;
        $this->point = $point;
    }

    public static function fromArray(array $detectionResult): Detection
    {
        Assert::notNull($detectionResult['label']);
        Assert::notNull($detectionResult['featureId']);
        Assert::notNull($detectionResult['bbox']);
        Assert::notNull($detectionResult['point']);

        return new self(
            $detectionResult['label'],
            $detectionResult['featureId'],
            $detectionResult['gender'] ?? null,
            BoundingBox::fromArray($detectionResult['bbox']),
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBox(): BoundingBox
    {
        return $this->box;
    }

    public function getPoint(): Point
    {
        return $this->point;
    }
}
