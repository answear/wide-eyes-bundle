<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Webmozart\Assert\Assert;

class SearchByFeatureResponse
{
    private array $uids;

    private function __construct(array $uids)
    {
        Assert::allString($uids);
        $this->uids = $uids;
    }

    public static function fromArray(array $response): SearchByFeatureResponse
    {
        try {
            if (!isset($response[0]['products'])) {
                throw new \InvalidArgumentException('Invalid response. Products not found');
            }

            $products = $response[0]['products'];
            $responseUids = array_map(
                static function ($item) {
                    Assert::keyExists($item, 'uid');

                    return $item['uid'];
                },
                $products,
            );

            return new self($responseUids);
        } catch (\Throwable $e) {
            throw new MalformedResponse($e->getMessage(), $response, $e);
        }
    }

    public function getUids(): array
    {
        return $this->uids;
    }
}
