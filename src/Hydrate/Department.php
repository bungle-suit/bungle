<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate;

use Bungle\DingTalk\Hydrate\Traits\DepartmentCommonFields;
use Bungle\Framework\Collection\CollectionUtil;
use Bungle\Framework\FP;
use Iterator;
use UnexpectedValueException;

class Department
{
    use DepartmentCommonFields;

    /**
     * Not available before self::toTree()
     *
     * @var Department[]
     */
    public array $children = [];

    /**
     * @param self[] $departments
     * @return Department root node of tree
     */
    public static function toTree(array $departments): Department
    {
        if (!$departments) {
            throw new UnexpectedValueException('Can not process empty Departments');
        }
        // to id keyed array
        $byIds = CollectionUtil::toKeyed(FP::attr('id'), $departments);

        $r = self::getRoot($byIds);
        self::treeFromRoot($r, $byIds);
        if (iterator_count($r->iterate()) !== count($departments)) {
            throw new UnexpectedValueException('Departments can not compose to a valid tree');
        }

        return $r;
    }

    /**
     * @param self[] $departments
     */
    private static function treeFromRoot(self $root, array $departments): void
    {
        $children = [];
        foreach ($departments as $item) {
            if ($item->parentId === $root->id) {
                $children[] = $item;
                self::treeFromRoot($item, $departments);
            }
        }
        $root->children = $children;
    }

    /**
     * @param self[] $byIds
     */
    private static function getRoot(array $byIds): self
    {
        foreach ($byIds as $item) {
            if (!array_key_exists($item->parentId, $byIds)) {
                return $item;
            }
        }
        throw new UnexpectedValueException('Can not find root node from Departments');
    }

    /**
     * Iterate self and all nested children.
     */
    public function iterate(): Iterator
    {
        yield $this;
        foreach ($this->children as $child) {
            yield from $child->iterate();
        }
    }
}
