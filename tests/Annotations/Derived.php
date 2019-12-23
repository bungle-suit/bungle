<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use Bungle\FrameworkBundle\Annotations\LogicName;

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
