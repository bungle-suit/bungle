<?php

declare(strict_types=1);

namespace Bungle\Framework\Tests\Annotations;

use Bungle\Framework\Annotation\High;
use Bungle\Framework\Annotation\LogicName;

/**
 * @LogicName("Order Bill")
 * @High("ent")
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

    // Use name as logic name if no LogicName annotation defined
    public string $name;

    /**
     * @LogicName("private_is_ok")
     */
    private string $includePrivate;

    protected string $includeProtected;

    public static string $ignoreStatic;
}