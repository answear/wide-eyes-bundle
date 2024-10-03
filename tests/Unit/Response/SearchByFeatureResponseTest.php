<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Response\SearchByFeatureResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SearchByFeatureResponseTest extends TestCase
{
    #[Test]
    public function correctlyReturnsUids(): void
    {
        $responseData = [
            [
                'products' => [
                    [
                        'uid' => 'uid1',
                    ],
                    [
                        'uid' => 'uid2',
                    ],
                    [
                        'uid' => 'uid3',
                    ],
                ],
            ],
        ];

        $response = SearchByFeatureResponse::fromArray($responseData);

        self::assertEquals(
            [
                'uid1',
                'uid2',
                'uid3',
            ],
            $response->uids
        );
    }

    #[Test]
    public function malformedResponseWithoutUidInOneItem(): void
    {
        $responseData = [
            [
                'products' => [
                    [
                        'uid' => 'uid1',
                    ],
                    [
                        'id' => 'uid2',
                    ],
                    [
                        'uid' => 'uid3',
                    ],
                ],
            ],
        ];

        $this->expectException(MalformedResponse::class);
        SearchByFeatureResponse::fromArray($responseData);
    }

    #[Test]
    public function malformedResponseWithoutUid(): void
    {
        $responseData = [
            [
                'products' => [
                    [
                        'result' => 'uid1',
                    ],
                    [
                        'result' => 'uid2',
                    ],
                ],
            ],
        ];

        $this->expectException(MalformedResponse::class);
        SearchByFeatureResponse::fromArray($responseData);
    }

    #[Test]
    public function malformedResponseWithoutProducts(): void
    {
        $responseData = [
            [
                [
                    'uid' => 'uid1',
                ],
                [
                    'uid' => 'uid2',
                ],
                [
                    'uid' => 'uid3',
                ],
            ],
        ];

        $this->expectException(MalformedResponse::class);
        SearchByFeatureResponse::fromArray($responseData);
    }
}
