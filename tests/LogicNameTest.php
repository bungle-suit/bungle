<?php

declare(strict_types=1);

namespace Mallows\LogicName\Tests;

use Mallows\LogicName\LogicName;
use PHPUnit\Framework\TestCase;

final class LogicNameTest extends TestCase
{
    public function testResolveClassName()
    {
        $this->assertEquals('Order Bill', LogicName::resolveClassName(Entity::class));
    }

    public function testResolveClassNameNoLogicName()
    {
        $this->assertEquals('LogicNameTest', LogicName::resolveClassName(self::class));
    }

    public function testGetShortClassName()
    {
        $this->assertEquals('Foo', LogicName::getShortClassName('Foo'));
        $this->assertEquals('Entity', LogicName::getShortClassName(Entity::class));
    }
}
