<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

readonly class DetectAndFeaturesRequest implements Request
{
    public function __construct(private string $image)
    {
    }

    public function toJson(): string
    {
        return json_encode(
            [
                'image' => $this->image,
            ],
            JSON_THROW_ON_ERROR
        );
    }
}
