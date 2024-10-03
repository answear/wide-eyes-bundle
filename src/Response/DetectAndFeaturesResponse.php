<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\ValueObject\Detection;
use Webmozart\Assert\Assert;

readonly class DetectAndFeaturesResponse
{
    /**
     * @param Detection[] $detections
     */
    private function __construct(public array $detections)
    {
        Assert::allIsInstanceOf($detections, Detection::class);
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
        } catch (\Throwable $throwable) {
            throw new MalformedResponse($throwable->getMessage(), $response, $throwable);
        }
    }
}
