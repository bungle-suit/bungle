<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Security;

final class RoleRegistry
{
    public array $defs = [];

    public function add(RoleDefinition $roleDef): void
    {
        assert(
            !self::roleExists($this->defs, $roleDef),
            "Duplicate role name: {$roleDef->name()}"
        );

        $this->defs[] = $roleDef;
    }

    public function adds(iterable $roleDefs): void
    {
        foreach ($roleDefs as $item) {
            $this->add($item);
        }
    }

    private static function roleExists(array $arr, RoleDefinition $role): bool
    {
        foreach ($arr as $item) {
            if ($item->name() === $role->name()) {
                return true;
            }
        }
        return false;
    }
}
