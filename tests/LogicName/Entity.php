<?php

declare(strict_types=1);

namespace Bungle\Framework\Tests\LogicName;

use Bungle\Framework\LogicName\LogicName;

/**
 * @LogicName("Order Bill")
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
