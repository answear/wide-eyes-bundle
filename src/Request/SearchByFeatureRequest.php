<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

class SearchByFeatureRequest implements Request
{
    private string $featureId;
    private string $label;
    private ?string $gender;
    private ?string $country;

    public function __construct(string $featureId, string $label, string $gender = null, string $country = null)
    {
        $this->featureId = $featureId;
        $this->label = $label;
        $this->gender = $gender;
        $this->country = $country;
    }

    public function toJson(): string
    {
        $toEncode = [
            'featureId' => $this->featureId,
            'label' => $this->label,
            'gender' => $this->gender,
            'filters' => $this->getCountryFilter(),
        ];

        return json_encode(array_filter($toEncode));
    }

    private function getCountryFilter(): ?string
    {
        if (null === $this->country) {
            return null;
        }

        return $this->country . '.inStock == true';
    }
}
