<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

class SearchByFeatureRequest implements Request
{
    private string $featureId;
    private string $label;
    private ?string $gender;
    private ?string $filters;
    private ?int $maxNumResults;

    public function __construct(string $featureId, string $label, ?string $gender = null, ?string $filters = null, ?int $maxNumResults = null)
    {
        $this->featureId = $featureId;
        $this->label = $label;
        $this->gender = $gender;
        $this->filters = $filters;
        $this->maxNumResults = $maxNumResults;
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
            )
        );
    }
}
