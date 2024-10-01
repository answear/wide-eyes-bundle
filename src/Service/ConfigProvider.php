<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

readonly class ConfigProvider
{
    public function __construct(
        public string $similarApiUrl,
        public string $searchByImageApiUrl,
        public string $publicKey,
        public float $similarRequestTimeout,
        public float $searchByImageRequestTimeout,
        public float $connectionTimeout,
    ) {
    }

    public function getRequestHeaders(): array
    {
        return [
            'Apikey' => $this->publicKey,
            'Content-Type' => 'application/json',
        ];
    }
}
