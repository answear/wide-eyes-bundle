<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Request;

use Answear\WideEyesBundle\Request\GetSimilarRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetSimilarRequestTest extends TestCase
{
    #[Test]
    public function requestIsCorrect(): void
    {
        $request = new GetSimilarRequest('uid', 'country');

        self::assertSame(
            '{"uid":"uid","country":"country"}',
            $request->toJson()
        );
    }
}
