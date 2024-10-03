<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\ValueObject;

use Webmozart\Assert\Assert;

readonly class Detection
{
    public function __construct(
        public string $label,
        public string $featureId,
        public ?string $gender,
        public BoundingBox $box,
        public Point $point,
    ) {
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
}
