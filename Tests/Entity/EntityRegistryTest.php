<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\ArrayEntityDiscovery;
use Bungle\FrameworkBundle\Entity\ArrayHighResolver;
use Bungle\FrameworkBundle\Exception\Exceptions;
use Bungle\FrameworkBundle\Exception\EntityNotFoundException;

final class EntityRegistryTest extends TestCase
{
    const ORDER = 'order\\order';
    const ORDER_LINE = 'order\\orderLine';

    public function testEntites(): void
    {
        $dis = new ArrayEntityDiscovery(
            $entites = [
              self::ORDER,
              self::ORDER_LINE,
            ]
        );
        $reg = new EntityRegistry($dis, new ArrayHighResolver([]));
        self::assertEquals($entites, $reg->entities);
    }

    public function testGetHigh(): void
    {
        $dis = new ArrayEntityDiscovery([
          $ord = self::ORDER,
          $ordLine = self::ORDER_LINE,
        ]);
        $resolver = new ArrayHighResolver([
          $ord => 'ord',
          $ordLine => 'oln',
        ]);
        $reg = new EntityRegistry($dis, $resolver);

        self::assertEquals($ord, $reg->getHigh('ord'));
    }

    public function testGetHighBadEntityClass(): void
    {
        $order = self::ORDER;
        $this->expectExceptionObject(EntityNotFoundException::entityClass($order));
        $reg = new EntityRegistry(
            new ArrayEntityDiscovery([ ]),
            new ArrayHighResolver([ ])
        );
        $reg->getHigh($order);
    }

    public function testGetHighNoHighDefined(): void
    {
        $order = self::ORDER;
        $this->expectExceptionObject(Exceptions::highNotDefined($order));

        $dis = new ArrayEntityDiscovery([$order]);
        $reg = new EntityRegistry($dis, new ArrayHighResolver([]));
        $reg->getHigh($order);
    }

    public function testDupHigh(): void
    {
        $this->expectExceptionObject(Exceptions::highDuplicated('ord', self::ORDER, self::ORDER_LINE));

        $dis = new ArrayEntityDiscovery([
          self::ORDER,
          self::ORDER_LINE,
        ]);
        $resolver = new ArrayHighResolver([
          self::ORDER => 'ord',
          self::ORDER_LINE => 'ord',
        ]);
        $reg = new EntityRegistry($dis, $resolver);
        $reg->getHigh(self::ORDER);
    }
}
