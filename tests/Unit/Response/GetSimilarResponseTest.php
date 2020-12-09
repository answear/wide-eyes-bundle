<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Response;

use Answear\WideEyesBundle\Exception\MalformedResponse;
use Answear\WideEyesBundle\Response\GetSimilarResponse;
use PHPUnit\Framework\TestCase;

class GetSimilarResponseTest extends TestCase
{
    /**
     * @test
     */
    public function correctlyReturnsUids(): void
    {
        $responseData = [
            [
                'uid' => 'uid1',
            ],
            [
                'uid' => 'uid2',
            ],
            [
                'uid' => 'uid3',
            ],
        ];

        $response = GetSimilarResponse::fromArray($responseData, 'uid1');

        self::assertEquals(
            [
                'uid2',
                'uid3',
            ],
            $response->getUids()
        );
    }

    /**
     * @test
     */
    public function malformedResponseWithoutUid(): void
    {
        $responseData = [
            [
                'result' => 'uid1',
            ],
        ];

        $this->expectException(MalformedResponse::class);
        GetSimilarResponse::fromArray($responseData, 'uid1');
    }
}
