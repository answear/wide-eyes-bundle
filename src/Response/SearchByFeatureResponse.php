<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Webmozart\Assert\Assert;

readonly class SearchByFeatureResponse
{
    /**
     * @param string[] $uids
     */
    private function __construct(public array $uids)
    {
        Assert::allString($uids);
    }

    public static function fromArray(array $response): SearchByFeatureResponse
    {
        if (!isset($response[0]['products']) || !is_array($response[0]['products'])) {
            throw new MalformedResponse('Empty result', $response);
        }

        try {
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
}
