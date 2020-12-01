<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Service\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    /**
     * @test
     */
    public function returnsCorrectRequestHeaders(): void
    {
        $configProvider = new ConfigProvider(
            'https://example.org/',
            'public_key',
            1.0,
            1.0
        );

        self::assertSame(
            [
                'Apikey' => 'public_key',
                'Content-Type' => 'application/json',
            ],
            $configProvider->getRequestHeaders()
        );
    }
}
