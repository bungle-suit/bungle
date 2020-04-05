<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate;

use Bungle\DingTalk\Hydrate\Department;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class DepartmentTest extends TestCase
{
    public function testListToTreeEmpty(): void
    {
        $this->expectException(UnexpectedValueException::class);
        Department::toTree([]);
    }

    public function testListToTree(): void
    {
        $list = [Department::create(1, 'root', 0)];
        $exp = clone $list[0];
        $act = Department::toTree($list);
        self::assertEquals($exp, $act);

        $list = [
            $ca1 = Department::create(4, 'ca1', 2),
            $root = Department::create(1, 'root', 0),
            $c1 = Department::create(2, 'c1', 1),
            $c2 = Department::create(3, 'c2', 1),
        ];
        list($root, $c1, $c2, $ca1) = [clone $root, $c1, $c2, $ca1];
        $root->children = [ $c1, $c2 ];
        $c1->children  = [$ca1];

        self::assertEquals($root, Department::toTree($list));
    }

    public function testGetManagerUserIds(): void
    {
        $exp = Department::create(2, 'xxx', 1);
        self::assertEquals([], $exp->getManagerUserIds());

        $exp->deptManagerUseridList = 'one';
        self::assertEquals(['one'], $exp->getManagerUserIds());

        $exp->deptManagerUseridList = 'manager1122|manager3211';
        self::assertEquals(['manager1122', 'manager3211'], $exp->getManagerUserIds());
    }
}
