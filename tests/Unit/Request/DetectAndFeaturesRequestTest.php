<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Request;

use Answear\WideEyesBundle\Request\DetectAndFeaturesRequest;
use PHPUnit\Framework\TestCase;

class DetectAndFeaturesRequestTest extends TestCase
{
    /**
     * @test
     */
    public function requestIsCorrect(): void
    {
        $request = new DetectAndFeaturesRequest('imageUrl');

        self::assertSame(
            '{"image":"imageUrl"}',
            $request->toJson()
        );
    }
}
