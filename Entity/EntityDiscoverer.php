<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class EntityDiscoverer implements EntityDiscovererInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getAllEntities(): \Iterator
    {
        $documentManager = $this->managerRegistry->getManager();
        return new \ArrayIterator($documentManager->getConfiguration()
                                          ->getMetadataDriverImpl()
                                          ->getAllClassNames());
    }
}
