<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use Bungle\FrameworkBundle\Annotations\LogicName;

class MixedTraits
{
    use Modifier;

    /**
     * @LogicName("数量")
     */
    public int $count;
}
