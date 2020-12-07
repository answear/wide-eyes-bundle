<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

class ConfigProvider
{
    private string $similarApiUrl;
    private string $searchByImageApiUrl;
    private string $publicKey;
    private float $requestTimeout;
    private float $connectionTimeout;

    public function __construct(
        string $similarApiUrl,
        string $searchByImageApiUrl,
        string $publicKey,
        float $requestTimeout,
        float $connectionTimeout
    ) {
        $this->similarApiUrl = $similarApiUrl;
        $this->searchByImageApiUrl = $searchByImageApiUrl;
        $this->publicKey = $publicKey;
        $this->requestTimeout = $requestTimeout;
        $this->connectionTimeout = $connectionTimeout;
    }

    public function getRequestHeaders(): array
    {
        return [
            'Apikey' => $this->getPublicKey(),
            'Content-Type' => 'application/json',
        ];
    }

    public function getSimilarApiUrl(): string
    {
        return $this->similarApiUrl;
    }

    public function getSearchByImageApiUrl(): string
    {
        return $this->searchByImageApiUrl;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getRequestTimeout(): float
    {
        return $this->requestTimeout;
    }

    public function getConnectionTimeout(): float
    {
        return $this->connectionTimeout;
    }
}
