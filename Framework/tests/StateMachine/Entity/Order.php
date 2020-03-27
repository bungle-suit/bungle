<?php

declare(strict_types=1);

namespace Bungle\Framework\Tests\StateMachine\Entity;

use Bungle\Framework\Annotation\High;
use Bungle\Framework\Entity\CommonTraits\ObjectID;
use Bungle\Framework\Entity\CommonTraits\Stateful;
use Bungle\Framework\Entity\CommonTraits\StatefulInterface;

/**
 * @High("ord")
 */
class Order implements StatefulInterface
{
    use ObjectID;
    use Stateful;

    public string $code;
}