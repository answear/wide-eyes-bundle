<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Webmozart\Assert\Assert;

readonly class GetSimilarResponse
{
    /**
     * @param string[] $uids
     */
    private function __construct(public array $uids)
    {
        Assert::allString($uids);
    }

    public static function fromArray(array $response, string $originalUid): GetSimilarResponse
    {
        try {
            $responseUids = array_map(
                static function ($item) {
                    Assert::keyExists($item, 'uid');

                    return $item['uid'];
                },
                $response,
            );

            return new self(
                array_values(
                    array_filter(
                        $responseUids,
                        static fn($item) => $item !== $originalUid,
                    )
                )
            );
        } catch (\Throwable $e) {
            throw new MalformedResponse($e->getMessage(), $response, $e);
        }
    }
}
