<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Request\GetSimilarRequest;
use Answear\WideEyesBundle\Response\GetSimilarResponse;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class SimilarClient extends AbstractClient
{
    public function __construct(ConfigProvider $configProvider, ClientInterface $client = null)
    {
        $this->configProvider = $configProvider;
        $this->guzzle = $client ?? new Client(
                [
                    'base_uri' => $this->configProvider->getSimilarApiUrl(),
                    'timeout' => $this->configProvider->getRequestTimeout(),
                ]
            );
    }

    public function getSimilar(string $uid, string $countyCode): GetSimilarResponse
    {
        return GetSimilarResponse::fromArray(
            $this->request(self::SEARCH_BY_ID_ENDPOINT, new GetSimilarRequest($uid, $countyCode)),
            $uid
        );
    }
}
