<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

class EntityRegistry {
  // array of entities full class name.
  public array $entities;

  public function __construct(EntityDiscovererInterface $discoverer)
  {
    $this->entities = iterator_to_array($discoverer->getAllEntities(), false);  
  }
}

