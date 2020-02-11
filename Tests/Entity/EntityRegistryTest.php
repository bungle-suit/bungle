<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\ArrayEntityDiscovery;
use Bungle\FrameworkBundle\Entity\ArrayHighResolver;

final class EntityRegistryTest extends TestCase {
  public function testEntites(): void
  {
    $dis = new ArrayEntityDiscovery(
      $entites = [
      'order\\order',
      'order\\orderLine',
    ]);
    $reg = new EntityRegistry($dis, new ArrayHighResolver([]));
    self::assertEquals($entites, $reg->entities);
  } 
}

