<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Request\DetectAndFeaturesRequest;
use Answear\WideEyesBundle\Response\DetectAndFeaturesResponse;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class SearchByImageClient extends AbstractClient
{
    public function __construct(ConfigProvider $configProvider, ClientInterface $client = null)
    {
        $this->configProvider = $configProvider;
        $this->guzzle = $client ?? new Client(
                [
                    'base_uri' => $this->configProvider->getSearchByImageApiUrl(),
                    'timeout' => $this->configProvider->getSearchByImageRequestTimeout(),
                ]
            );
    }

    public function detectAndFeatures(string $image): DetectAndFeaturesResponse
    {
        return DetectAndFeaturesResponse::fromArray(
            $this->request(self::DETECT_AND_FEATURES_ENDPOINT, new DetectAndFeaturesRequest($image))
        );
    }
}
