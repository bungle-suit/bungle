<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use Bungle\FrameworkBundle\Annotations\LogicName;
use Bungle\FrameworkBundle\Annotations\HighPrefix;

/**
 * @LogicName("Order Bill")
 * @HighPrefix("ent")
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
