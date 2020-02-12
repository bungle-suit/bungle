<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

/**
 * Interface support to discover all entity class full names.
 */
interface EntityDiscovererInterface
{
    public function getAllEntities(): \Iterator;
}
