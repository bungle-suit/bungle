<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use Bungle\FrameworkBundle\Annotation\LogicName;

trait Modifier
{
    /**
     * @LogicName("修改人")
     */
    public string $modifier;

    /**
     * @LogicName("修改时间")
     */
    public \Date $modifyTime;
}
