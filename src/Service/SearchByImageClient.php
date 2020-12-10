<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Request\DetectAndFeaturesRequest;
use Answear\WideEyesBundle\Request\SearchByFeatureRequest;
use Answear\WideEyesBundle\Response\DetectAndFeaturesResponse;
use Answear\WideEyesBundle\Response\SearchByFeatureResponse;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class SearchByImageClient extends AbstractClient
{
    private const DETECT_AND_FEATURES_ENDPOINT = 'v4/DetectAndFeatures';
    private const SEARCH_BY_FEATURE = 'v4/SearchByFeature';

    public function __construct(ConfigProvider $configProvider, ?ClientInterface $client = null)
    {
        parent::__construct(
            $configProvider,
            $client ?? new Client(
                [
                    'base_uri' => $configProvider->getSearchByImageApiUrl(),
                    'timeout' => $configProvider->getSearchByImageRequestTimeout(),
                ]
            )
        );
    }

    public function detectAndFeatures(string $image): DetectAndFeaturesResponse
    {
        return DetectAndFeaturesResponse::fromArray(
            $this->request(self::DETECT_AND_FEATURES_ENDPOINT, new DetectAndFeaturesRequest($image))
        );
    }

    public function searchByFeature(
        string $featureId,
        string $label,
        ?string $gender = null,
        ?string $country = null
    ): SearchByFeatureResponse {
        return SearchByFeatureResponse::fromArray(
            $this->request(self::SEARCH_BY_FEATURE, new SearchByFeatureRequest($featureId, $label, $gender, $country))
        );
    }
}
