<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

/**
 * Simple EntityDiscover implementation, returns entity specified
 * through constructor.
 *
 * Use it is simple use cases or unit tests.
 */
class SimpleEntityDiscover implements EntityDiscover
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
