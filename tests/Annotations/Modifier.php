<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\Annotations;

use Bungle\Framework\Annotations\LogicName;

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
