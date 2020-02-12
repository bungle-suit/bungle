<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

final class EntityMeta
{
    public array $properties;

    // Entity class name with namespace.
    public string $fullName;

    public string $logicName;

    public function __construct(string $fullName, string $logicName, array $properties)
    {
        $this->fullName = $fullName;
        $this->logicName =$logicName;
        $this->properties = $properties;
    }

    // Get property meta by name, raise error if no such property.
    public function getProperty(string $name): EntityPropertyMeta
    {
        //
    }

    // Entity class name without namespace.
    public function name(): string
    {
        $words = explode('\\', $this->fullName);
        return $words[array_key_last($words)];
    }
}
