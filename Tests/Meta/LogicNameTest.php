<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Meta;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Meta\LogicName;
use Bungle\FrameworkBundle\Meta\SimpleEntityLocator;

final class LogicNameTest extends TestCase
{
    public function test(): void
    {
        $names = new LogicName(new SimpleEntityLocator([Order::class]));

        $orderName = $names->get(Order::class);
        self::assertEquals('Order', $orderName->logicName());
    }

    public function testGetNotExist(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $names = new LogicName(new SimpleEntityLocator([]));
        $names->get(Order::class);
    }
}
