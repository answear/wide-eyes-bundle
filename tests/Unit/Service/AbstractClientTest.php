<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Service;

use Answear\WideEyesBundle\Service\ConfigProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;

abstract class AbstractClientTest extends TestCase
{
    /**
     * @see http://docs.guzzlephp.org/en/stable/testing.html#history-middleware
     */
    protected $guzzleHistory;
    protected MockHandler $guzzleHandler;
    protected ConfigProvider $configProvider;

    private const API_URL = 'https://wideeyes-fake-api.test/';
    private const API_KEY = 'api-key';

    public function setUp(): void
    {
        parent::setUp();

        $this->configProvider = new ConfigProvider(
            self::API_URL,
            self::API_URL,
            self::API_KEY,
            1.0,
            1.0
        );
    }

    protected function setupGuzzle(): ClientInterface
    {
        $this->guzzleHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->guzzleHandler);

        $this->guzzleHistory = [];
        $history = Middleware::history($this->guzzleHistory);
        $handlerStack->push($history);

        return new \GuzzleHttp\Client(['handler' => $handlerStack]);
    }
}
