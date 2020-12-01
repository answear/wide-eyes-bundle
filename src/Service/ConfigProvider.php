<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

class ConfigProvider
{
    private string $apiUrl;
    private string $publicKey;
    private float $requestTimeout;
    private float $connectionTimeout;

    public function __construct(
        string $apiUrl,
        string $publicKey,
        float $requestTimeout,
        float $connectionTimeout
    ) {
        $this->apiUrl = $apiUrl;
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

    public function getApiUrl(): string
    {
        return $this->apiUrl;
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
