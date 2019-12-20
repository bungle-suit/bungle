<?php

declare(strict_types=1);

namespace Mallows\LogicName\Tests;

use Mallows\LogicName\LogicName;
use PHPUnit\Framework\TestCase;

/**
 * @LogicName("Order Bill")
 */
class Entity {
}

final class LogicNameTest extends TestCase {
  function testResolveClassName() {
    $this->assertEquals('Order Bill', LogicName::resolveClassName(Entity::class));
  }

  function testResolveClassNameNoLogicName() {
    $this->assertEquals('LogicNameTest', LogicName::resolveClassName(self::class));
  }

  function testGetShortClassName() {
    $this->assertEquals('Foo', LogicName::getShortClassName('Foo'));
    $this->assertEquals('Entity', LogicName::getShortClassName(Entity::class));
  }
}
