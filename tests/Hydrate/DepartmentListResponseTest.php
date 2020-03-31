<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate;

use Bungle\DingTalk\Hydrate\Department;
use Bungle\DingTalk\Hydrate\DepartmentListResponse;
use PHPUnit\Framework\TestCase;

class DepartmentListResponseTest extends TestCase
{
    public function testHydrate(): void
    {
        $input = [
            'errcode' => 1,
            'errmsg' => 'ok',
            'department' => [
                [
                    'id' => 1,
                    'name' => 'root',
                    'createDeptGroup' => true,
                    'autoAddUser' => true,
                    'ext' => '{"deptNo":1}',
                ],
                [
                    'id' => 2,
                    'name' => 'xxx',
                    'parentid' => 1,
                    'createDeptGroup' => true,
                    'autoAddUser' => true,
                    'ext' => '{"deptNo":1}',
                ],
                [
                    'id' => 3,
                    'name' => '服务端开发组',
                    'parentid' => 2,
                    'createDeptGroup' => false,
                    'autoAddUser' => false,
                    'ext' => '{"deptNo":2}',
                ],
            ],
        ];
        $exp = new DepartmentListResponse();
        $exp->errorMessage = 'ok';
        $exp->errorCode = 1;
        $exp->departments = [
            Department::create(1, 'root', 0),
            Department::create(2, 'xxx', 1),
            Department::create(3, '服务端开发组', 2),
        ];

        self::assertEquals($exp, DepartmentListResponse::hydrate($input));
    }
}
