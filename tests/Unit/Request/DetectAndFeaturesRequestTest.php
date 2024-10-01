<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Request;

use Answear\WideEyesBundle\Request\DetectAndFeaturesRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DetectAndFeaturesRequestTest extends TestCase
{
    #[Test]
    public function requestIsCorrect(): void
    {
        $request = new DetectAndFeaturesRequest('imageUrl');

        self::assertSame(
            '{"image":"imageUrl"}',
            $request->toJson()
        );
    }
}
