<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Request\DetectAndFeaturesRequest;
use Answear\WideEyesBundle\Request\GetSimilarRequest;
use Answear\WideEyesBundle\Request\Request;
use Answear\WideEyesBundle\Response\DetectAndFeaturesResponse;
use Answear\WideEyesBundle\Response\GetSimilarResponse;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Webmozart\Assert\Assert;

class Client
{
    private const SEARCH_BY_ID_ENDPOINT = 'v4/SearchById';
    private const DETECT_AND_FEATURES_ENDPOINT = 'v4/DetectAndFeatures';

    private ConfigProvider $configProvider;
    private ?ClientInterface $guzzle;

    public function __construct(ConfigProvider $configProvider, ClientInterface $client = null)
    {
        $this->configProvider = $configProvider;
        $this->guzzle = $client ?? new \GuzzleHttp\Client(
                [
                    'base_uri' => $this->configProvider->getApiUrl(),
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

    public function detectAndFeatures(string $image): DetectAndFeaturesResponse
    {
        return DetectAndFeaturesResponse::fromArray(
            $this->request(self::DETECT_AND_FEATURES_ENDPOINT, new DetectAndFeaturesRequest($image))
        );
    }

    private function request(string $endpoint, Request $request): array
    {
        try {
            $response = $this->guzzle->request(
                'POST',
                $endpoint,
                [
                    'headers' => $this->configProvider->getRequestHeaders(),
                    'body' => $request->toJson(),
                ]
            );
            if ($response->getBody()->isSeekable()) {
                $response->getBody()->rewind();
            }
            $responseText = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new ServiceUnavailable($e->getMessage(), $e->getCode(), $e);
        }

        $decoded = \json_decode($responseText, true);

        try {
            Assert::isArray($decoded);
            Assert::keyExists($decoded, 'results');
        } catch (\InvalidArgumentException $e) {
            throw new MalformedResponse($e->getMessage(), $decoded, $e);
        }

        return $decoded['results'];
    }
}
