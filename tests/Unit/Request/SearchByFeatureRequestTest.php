<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Tests\Unit\Request;

use Answear\WideEyesBundle\Request\SearchByFeatureRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SearchByFeatureRequestTest extends TestCase
{
    #[Test]
    public function requestWithoutGenderAndCountryIsCorrect(): void
    {
        $request = new SearchByFeatureRequest('featureId', 'label');

        self::assertSame(
            '{"featureId":"featureId","label":"label"}',
            $request->toJson()
        );
    }

    #[Test]
    public function requestWithGenderAndCountryIsCorrect(): void
    {
        $request = new SearchByFeatureRequest('featureId', 'label', 'female', 'pl.inStock == true');

        self::assertSame(
            '{"featureId":"featureId","label":"label","gender":"female","filters":"pl.inStock == true"}',
            $request->toJson()
        );
    }

    #[Test]
    public function requestWithMaxNumResultsIsCorrect(): void
    {
        $request = new SearchByFeatureRequest('featureId', 'label', null, null, 30);

        self::assertSame(
            '{"featureId":"featureId","label":"label","maxNumResults":30}',
            $request->toJson()
        );
    }
}
