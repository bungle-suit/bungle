<?php

declare(strict_types=1);

namespace Mallows\Framework\Tests\LogicName;

use Mallows\Framework\LogicName\LogicName;

class Derived extends Entity
{
  /**
   * @LogicName("New Counter")
   */
    public int $count;

  /**
   * @LogicName("地址")
   */
    public string $address;
}
