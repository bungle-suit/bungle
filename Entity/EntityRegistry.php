<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

use Bungle\FrameworkBundle\Exception\Exceptions;
use Bungle\FrameworkBundle\Exception\EntityNotFoundException;

class EntityRegistry
{
    // array of entities full class name.
    public array $entities;
    private HighResolverInterface $highResolver;
    private array $highClsMap;

    public function __construct(EntityDiscovererInterface $discoverer, HighResolverInterface $highResolver)
    {
        $this->highResolver = $highResolver;
        $this->entities = iterator_to_array($discoverer->getAllEntities(), false);
    }

    /**
     * Get high prefix by clsName.
     */
    public function getHigh(string $clsName): string
    {
        if (!isset($this->highClsMap)) {
            $this->highClsMap = $this->scanMap($this->entities);
        }

        if (!($r = $this->highClsMap[$clsName] ?? '')) {
            if (!\in_array($clsName, $this->entities)) {
                throw EntityNotFoundException::entityClass($clsName);
            }
        }
        return $r;
    }

    private function scanMap(array $entities): array
    {
        $r = [];
        foreach ($entities as $cls) {
            $high = $this->highResolver->resolveHigh($cls);
            if (!$high) {
                throw Exceptions::highNotDefined($cls);
            }

            if (array_key_exists($high, $r)) {
                throw Exceptions::highDuplicated($high, $r[$high], $cls);
            }
            $r[$high] = $cls;
        }
        return $r;
    }
}
