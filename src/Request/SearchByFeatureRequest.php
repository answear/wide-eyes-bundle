<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

readonly class SearchByFeatureRequest implements Request
{
    public function __construct(
        private string $featureId,
        private string $label,
        private ?string $gender = null,
        private ?string $filters = null,
        private ?int $maxNumResults = null,
    ) {
    }

    public function toJson(): string
    {
        return json_encode(
            array_filter(
                [
                    'featureId' => $this->featureId,
                    'label' => $this->label,
                    'gender' => $this->gender,
                    'filters' => $this->filters,
                    'maxNumResults' => $this->maxNumResults,
                ]
            ),
            JSON_THROW_ON_ERROR
        );
    }
}
