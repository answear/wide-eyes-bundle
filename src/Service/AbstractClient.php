<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Exception\ServiceUnavailable;
use Answear\WideEyesBundle\Request\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

abstract class AbstractClient
{
    protected ConfigProvider $configProvider;
    protected ClientInterface $guzzle;

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
            $responseText = $this->getResponseText($response);
        } catch (GuzzleException $e) {
            throw new ServiceUnavailable($e->getMessage(), $e->getCode(), $e);
        }

        return $this->getResult($responseText);
    }

    /**
     * @param Request[] $requests
     */
    protected function manyRequests(string $endpoint, array $requests): array
    {
        $promises = [];

        foreach ($requests as $key => $request) {
            $promises[$key] = $this->guzzle->requestAsync(
                'POST',
                $endpoint,
                [
                    'headers' => $this->configProvider->getRequestHeaders(),
                    'body' => $request->toJson(),
                ]
            );
        }

        $promisesResults = Promise\settle($promises)->wait();

        $processedResults = [];
        foreach ($promisesResults as $key => $result) {
            if ('fulfilled' === $result['state']) {
                try {
                    $processedResults[$key] = $this->getResult($this->getResponseText($result['value']));
                } catch (MalformedResponse $e) {
                    $processedResults[$key] = [];
                }
            }
        }

        return $processedResults;
    }

    private function getResponseText(ResponseInterface $response): string
    {
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }

        return $response->getBody()->getContents();
    }

    private function getResult(string $responseText): array
    {
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
