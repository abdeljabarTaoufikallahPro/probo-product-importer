<?php

namespace App\Model;

readonly class Name
{
    public function __construct(public ?string $value, public ?string $language)
    {
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }
}
