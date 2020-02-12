<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityMetaRepository;
use Bungle\FrameworkBundle\Entity\EntityMeta;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\EntityMetaResolverInterface;
use Bungle\FrameworkBundle\Entity\ArrayEntityDiscovery;
use Bungle\FrameworkBundle\Entity\ArrayHighResolver;
use Bungle\FrameworkBundle\Entity\ArrayEntityMetaResolver;

final class EntityMetaRepositoryTest extends TestCase
{
  
    public function testGetMeta(): void
    {
        $reg = new EntityRegistry(
            new ArrayEntityDiscovery([self::class]),
            new ArrayHighResolver([self::class => 'ord'])
        );
        $rep = new EntityMetaRepository(
            $reg,
            new ArrayEntityMetaResolver([
            self::class => ($meta = new EntityMeta(self::class, 'foo', []))
            ]),
        );

        self::assertSame($meta, $rep->get('ord'));
        self::assertSame($meta, $rep->get('ord')); // does cache works
    }
}
