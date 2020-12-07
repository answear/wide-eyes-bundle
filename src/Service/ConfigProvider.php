<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

class ConfigProvider
{
    private string $similarApiUrl;
    private string $searchByImageApiUrl;
    private string $publicKey;
    private float $similarRequestTimeout;
    private float $searchByImageRequestTimeout;
    private float $connectionTimeout;

    public function __construct(
        string $similarApiUrl,
        string $searchByImageApiUrl,
        string $publicKey,
        float $similarRequestTimeout,
        float $searchByImageRequestTimeout,
        float $connectionTimeout
    ) {
        $this->similarApiUrl = $similarApiUrl;
        $this->searchByImageApiUrl = $searchByImageApiUrl;
        $this->publicKey = $publicKey;
        $this->similarRequestTimeout = $similarRequestTimeout;
        $this->searchByImageRequestTimeout = $searchByImageRequestTimeout;
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

    public function getSimilarRequestTimeout(): float
    {
        return $this->similarRequestTimeout;
    }

    public function getSearchByImageRequestTimeout(): float
    {
        return $this->searchByImageRequestTimeout;
    }

    public function getConnectionTimeout(): float
    {
        return $this->connectionTimeout;
    }
}
