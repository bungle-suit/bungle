<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

/**
 * Simple EntityLocator implementation, returns entity specified
 * through constructor.
 *
 * Use it is simple use cases or unit tests.
 */
class SimpleEntityLocator implements EntityLocator
{
    private array $entityClasses;

    public function __construct(array $entityClasses)
    {
        $this->entityClasses = $entityClasses;
    }

    public function getAllEntities(): array
    {
        return $this->entityClasses;
    }
}
