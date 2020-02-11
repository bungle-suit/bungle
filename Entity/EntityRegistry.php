<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

class EntityRegistry {
  // array of entities full class name.
  public array $entities;
  private HighResolverInterface $highResolver;

  public function __construct(EntityDiscovererInterface $discoverer, HighResolverInterface $highResolver)
  {
    $this->entities = iterator_to_array($discoverer->getAllEntities(), false);  
  }
}

