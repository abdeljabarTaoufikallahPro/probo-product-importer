<?php

namespace App\Model;

use Ramsey\Uuid\Uuid;

class Option
{
    public string $id;
    public string $code;
    public string $typeCode;
    public bool $isParent;
    public ?Option $parent = null;
    public array $nameTranslations = [];

    public function __construct(array $data)
    {
        $this->id = Uuid::uuid4();
        $this->code = $data['code'];
        $this->typeCode = $data['type_code'];
        $this->isParent = $data['is_parent'] ?? false;
        foreach ($data['translations'] as $language => $translation) {
            $this->nameTranslations[] = new Name($translation['name'], $language);
        }
    }

    public function isParent(): bool
    {
        return $this->isParent;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setParent(Option $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?Option
    {
        return $this->parent;
    }

    public function getTypeCode(): string
    {
        return $this->typeCode;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
