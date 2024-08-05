<?php

namespace App\Service;

use App\Model\Option;

class OptionFlattener
{
    private array $properties;
    private array $values;

    public function __construct(private readonly array $options)
    {
        $this->properties = [];
        $this->values = [];
    }

    public function flatten(): void
    {
        $this->flattenRecursive($this->options);
    }

    public function flattenRecursive(array $options, Option $parent = null): void
    {
        foreach ($options as $optionData) {
            $option = new Option($optionData);

            if ($parent) {
                $option->setParent($parent);
            }

            if ($option->isParent()) {
                $this->properties[] = $option;
            } else {
                $this->values[] = $option;
            }

            if (!empty($optionData['children'])) {
                $childrenData = $optionData['children'];
                $this->flattenRecursive($childrenData, $option);
            }
        }
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
