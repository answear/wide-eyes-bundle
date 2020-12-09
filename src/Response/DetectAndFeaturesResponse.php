<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\ValueObject\Detection;
use Webmozart\Assert\Assert;

class DetectAndFeaturesResponse
{
    /**
     * @var Detection[]
     */
    private array $detections;

    private function __construct(array $detections)
    {
        Assert::allIsInstanceOf($detections, Detection::class);
        $this->detections = $detections;
    }

    public static function fromArray(array $response): DetectAndFeaturesResponse
    {
        try {
            Assert::keyExists($response, 'detections');

            return new self(
                array_map(
                    static function ($item) {
                        return Detection::fromArray($item);
                    },
                    $response['detections'],
                )
            );
        } catch (\Throwable $e) {
            throw new MalformedResponse($e->getMessage(), $response, $e);
        }
    }

    public function getDetections(): array
    {
        return $this->detections;
    }
}
