<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Request;

use Answear\WideEyesBundle\Request\SearchByFeatureRequest;
use PHPUnit\Framework\TestCase;

class SearchByFeatureRequestTest extends TestCase
{
    /**
     * @test
     */
    public function requestWithoutGenderAndCountryIsCorrect(): void
    {
        $request = new SearchByFeatureRequest('featureId', 'label');

        self::assertSame(
            '{"featureId":"featureId","label":"label"}',
            $request->toJson()
        );
    }

    /**
     * @test
     */
    public function requestWithGenderAndCountryIsCorrect(): void
    {
        $request = new SearchByFeatureRequest('featureId', 'label', 'female', 'pl');

        self::assertSame(
            '{"featureId":"featureId","label":"label","gender":"female","filters":"pl.inStock == true"}',
            $request->toJson()
        );
    }
}
