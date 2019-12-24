<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

use PHPUnit\Framework\TestCase;

/**
 * Contains parsed LogicName for a Entity class.
 */
class EntityLogicName
{
    private string $logicName;
    private array $propLogicNames;
    private string $entityCls;

    public function __construct(string $entityCls, string $logicName, array $propLogicNames)
    {
        $this->logicName = $logicName;
        $this->entityCls = $entityCls;
        $this->propLogicNames = $propLogicNames;
    }

    /**
     * Returns logicName of the entity class.
     */
    public function logicName(): string
    {
        return $this->logicName;
    }

    /**
     * Return logic name of specific property.
     *
     * Assert if property exist.
     */
    public function getPropertyLogicName(string $propName): string
    {
        assert(
            isset($this->propLogicNames[$propName]),
            "No LogicName for '$this->entityCls'/'$propName'"
        );
        return $this->propLogicNames[$propName];
    }
}
