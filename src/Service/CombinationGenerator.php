<?php

namespace App\Service;

class CombinationGenerator
{
    private array $properties;
    private array $values;
    private array $combinations;
    private array $currentCombination;

    public function __construct(array $properties, array $values)
    {
        $this->properties = $properties;
        $this->values = $values;
        $this->currentCombination = [];
        $this->combinations = [];
    }

    public function generate(): void
    {
        $mainProperties = array_filter(
            $this->properties,
            fn ($property) => $property->getParent() === null
        );

        $this->generateCombinationsRecursive($mainProperties);
    }

    private function generateCombinationsRecursive(array $properties): void
    {
        if (empty($properties)) {
            $this->combinations[] = $this->currentCombination;
            return;
        }

        $property = array_shift($properties);
        $propertyValues = array_filter(
            $this->values,
            fn ($value) => $value->getParent() && $value->getParent()->getId() === $property->getId()
        );

        if (empty($propertyValues)) {
            // Handle case where no values found for the property
            $this->generateCombinationsRecursive($properties);
            return;
        }

        foreach ($propertyValues as $value) {
            $this->currentCombination[] = $value;

            $childProperties = array_filter(
                $this->properties,
                fn ($childProperty) => $childProperty->getParent() && $childProperty->getParent()->getId() === $value->getId()
            );

            // Handle multiple child properties
            if (!empty($childProperties)) {
                $this->generateCombinationsRecursive($childProperties);
            } else {
                $this->generateCombinationsRecursive($properties);
            }

            array_pop($this->currentCombination);
        }
    }

    public function getCombinations(): array
    {
        return $this->combinations;
    }
}
