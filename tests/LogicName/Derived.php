<?php

declare(strict_types=1);

namespace Bungle\Framework\Tests\LogicName;

use Bungle\Framework\LogicName\LogicName;

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
