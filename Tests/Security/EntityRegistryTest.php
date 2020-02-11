<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\ArrayEntityDiscovery;

final class EntityRegistryTest extends TestCase {
  public function testEntites(): void
  {
    $dis = new ArrayEntityDiscovery(
      $entites = [
      'order\\order',
      'order\\orderLine',
    ]);
    $reg = new EntityRegistry($dis);
    self::assertEquals($entites, $reg->entities);
  } 
}

