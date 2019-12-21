<?php
declare(strict_types=1);

namespace Mallows\Framework\Tests\LogicName;

use Mallows\Framework\LogicName\LogicName;

class MixedTraits
{
    use Modifier;

    /**
     * @LogicName("数量")
     */
    public int $count;
}
