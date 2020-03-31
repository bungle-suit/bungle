<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate;

use Bungle\DingTalk\Hydrate\DepartmentResponse;
use PHPUnit\Framework\TestCase;

class DepartmentResponseTest extends TestCase
{
    public function testHydrate(): void
    {
        $input = [
            'errcode' => 0,
            'errmsg' => 'ok',
            'id' => 2,
            'name' => 'xxx',
            'order' => 10,
            'parentid' => 1,
            'createDeptGroup' => true,
            'autoAddUser' => true,
            'deptHiding' => true,
            'deptPermits' => '3|4',
            'userPermits' => 'userid1|userid2',
            'outerDept' => true,
            'outerPermitDepts' => '1|2',
            'outerPermitUsers' => 'userid3|userid4',
            'orgDeptOwner' => 'manager1122',
            'deptManagerUseridList' => 'manager1122|manager3211',
            'sourceIdentifier' => 'source',
            'ext' => '{"deptNo":1}',
        ];
        $exp = DepartmentResponse::create(2, 'xxx', 1);
        $exp->order = 10;
        $exp->errorMessage = 'ok';
        $exp->deptManagerUseridList = 'manager1122|manager3211';
        self::assertEquals($exp, DepartmentResponse::hydrate($input));
    }

    public function testGetManagerUserIds(): void
    {
        $exp = DepartmentResponse::create(2, 'xxx', 1);
        self::assertEquals([], $exp->getManagerUserIds());

        $exp->deptManagerUseridList = 'one';
        self::assertEquals(['one'], $exp->getManagerUserIds());

        $exp->deptManagerUseridList = 'manager1122|manager3211';
        self::assertEquals(['manager1122', 'manager3211'], $exp->getManagerUserIds());
    }

}
