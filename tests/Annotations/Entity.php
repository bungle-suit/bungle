<?php

declare(strict_types=1);

namespace Bungle\Framework\Tests\Annotations;

use Bungle\Framework\Annotations\LogicName;
use Bungle\Framework\Annotations\HighPrefix;

/**
 * @LogicName("Order Bill")
 * @HighPrefix("基础实体对象")
 */
class Entity
{
    /**
     * @LogicName("ID")
     */
    public string $id;

    /**
     * @LogicName("Counter")
     */
    public int $count;

    # Use name as logic name if no LogicName annotation defined
    public string $name;

    private string $ignorePrivate;

    protected string $ignoreProtected;

    public static string $ignoreStatic;
}
