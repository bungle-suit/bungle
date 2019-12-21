<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\LogicName;

use Bungle\Framework\LogicName\LogicName;

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
