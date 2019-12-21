<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\LogicName;

use Bungle\Framework\LogicName\LogicName;

class MixedTraits
{
    use Modifier;

    /**
     * @LogicName("数量")
     */
    public int $count;
}
