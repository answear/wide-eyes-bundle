<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

class GetSimilarRequest implements Request
{
    private string $uid;
    private string $country;

    public function __construct(string $uid, string $country)
    {
        $this->uid = $uid;
        $this->country = $country;
    }

    public function toJson(): string
    {
        return json_encode(
            [
                'uid' => $this->uid,
                'country' => $this->country,
            ]
        );
    }
}
