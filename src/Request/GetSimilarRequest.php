<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

class GetSimilarRequest implements Request
{
    private string $uid;
    private string $country;
    private ?int $maxNumResults;

    public function __construct(string $uid, string $country, ?int $maxNumResults = null)
    {
        $this->uid = $uid;
        $this->country = $country;
        $this->maxNumResults = $maxNumResults;
    }

    public function toJson(): string
    {
        $resultArray = [
            'uid' => $this->uid,
            'country' => $this->country
        ];

        if (null !== $this->maxNumResults) {
            $resultArray['maxNumResults'] = $this->maxNumResults;
        }

        return json_encode($resultArray);
    }
}
