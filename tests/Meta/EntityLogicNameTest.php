<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Meta;

use Bungle\FrameworkBundle\Meta\EntityLogicName;
use PHPUnit\Framework\TestCase;

class EntityLogicNameTest extends TestCase
{
    public function test(): void
    {
        $meta = new EntityLogicName(Order::class, 'Order', ['id' => 'ID']);
        self::assertEquals('Order', $meta->logicName());

        self::assertEquals('ID', $meta->getPropertyLogicName('id'));
    }

    public function testGetPropertyLogicNameNotDefined(): void
    {
        self::expectException(\AssertionError::class);
    
        $meta = new EntityLogicName(Order::class, 'Order', ['id' => 'ID']);
        $meta->getPropertyLogicName('foo');
    }
}
