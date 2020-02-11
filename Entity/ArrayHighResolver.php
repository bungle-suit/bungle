<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

/**
 * Resolve high from an map(array).
 */
class ArrayHighResolver implements HighResolverInterface {
  private array $highs;

  public function __construct(array $highs)
  {
    $this->highs = $highs; 
  } 

  public function resolveHigh(String $entityCls): ?String
  {
    return $this->highs[$entityCls] ?? null;    
  }
}

