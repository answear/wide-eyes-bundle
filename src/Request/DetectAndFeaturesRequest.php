<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

class DetectAndFeaturesRequest implements Request
{
    private string $image;

    public function __construct(string $image)
    {
        $this->image = $image;
    }

    public function toJson(): string
    {
        return json_encode(
            [
                'image' => $this->image,
            ]
        );
    }
}
