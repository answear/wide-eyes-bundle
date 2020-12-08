<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Request\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Webmozart\Assert\Assert;

abstract class AbstractClient
{
    protected const SEARCH_BY_ID_ENDPOINT = 'v4/SearchById';
    protected const DETECT_AND_FEATURES_ENDPOINT = 'v4/DetectAndFeatures';

    protected ConfigProvider $configProvider;
    protected ?ClientInterface $guzzle;

    public function __construct(ConfigProvider $configProvider, ClientInterface $client)
    {
        $this->configProvider = $configProvider;
        $this->guzzle = $client;
    }

    protected function request(string $endpoint, Request $request): array
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
