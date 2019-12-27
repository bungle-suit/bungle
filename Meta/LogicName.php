<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

use Bungle\FrameworkBundle\Annotation\LogicName as LogicNameAnno;

/**
 * LogicName service provide ops related to logic names.
 */
final class LogicName
{
    // entity class -> EntityLogicName instances
    private array $entities;

    public function __construct(EntityLocator $discover)
    {
        // TODO: generate all source code on entity classes changes,
        // generate *all* source code in constructor to discover
        // all possible errors of entity logic name annotations.
        // Generate one file for each entity class, to
        // not load EntityLogicName for all entity classes.
        $this->entities = self::scan($discover);
    }

    /**
     * Get Entity LogicName by entity class name.
     *
     * Raise exception if the entity class failed to resolve.
     */
    public function get(string $entityCls): EntityLogicName
    {
        if (!isset($this->entities[$entityCls])) {
            throw new \InvalidArgumentException("No LogicName for entity '$entityCls'");
        }

        return $this->entities[$entityCls];
    }

    private static function scan(EntityLocator $discover): array
    {
        $r = [];
        $entities = $discover->getAllEntities();
        foreach ($entities as $cls) {
            $clsLogicName = LogicNameAnno::resolveClassName($cls);
            $propNames = LogicNameAnno::resolvePropertyNames($cls);
            $r[$cls] = new EntityLogicName($cls, $clsLogicName, $propNames);
        }
        return $r;
    }
}
