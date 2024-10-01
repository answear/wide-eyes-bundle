<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

readonly class GetSimilarRequest implements Request
{
    public function __construct(
        private string $uid,
        private string $country,
    ) {
    }

    public function toJson(): string
    {
        return json_encode(
            [
                'uid' => $this->uid,
                'country' => $this->country,
            ],
            JSON_THROW_ON_ERROR
        );
    }
}
