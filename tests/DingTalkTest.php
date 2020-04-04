<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests;

use Bungle\DingTalk\DingTalk;
use Bungle\DingTalk\DingTalkException;
use Bungle\DingTalk\Hydrate\Department;
use EasyDingTalk\Application;
use EasyDingTalk\Department\Client as DepartmentClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class DingTalkTest extends MockeryTestCase
{
    /** @var Application|Mockery\LegacyMockInterface|Mockery\MockInterface  */
    private $app;
    private DingTalk $dd;
    /** @var DepartmentClient|Mockery\LegacyMockInterface|Mockery\MockInterface  */
    private $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = Mockery::mock(Application::class);
        $this->department = Mockery::mock(DepartmentClient::class);
        $this->app->department = $this->department;
        $this->dd = new DingTalk($this->app);
    }

    public function testGetDepartmentListFailed()
    {
        $this->expectException(DingTalkException::class);

        $this
            ->department
            ->expects('list')
            ->with()
            ->andReturn(
                [
                    'errcode' => 10,
                    'errmsg' => 'wrong key',
                    'department' =>[],
                ]
            )
        ;

        $this->dd->getDepartmentTree();
    }

    public function testGetDepartment(): void
    {
        $this
            ->department
            ->expects('list')
            ->with()
            ->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'department' =>[
                        [
                            'id' => 1,
                            'name' => 'root',
                            'parentid' => 0,
                            'ext' => 'ext12',
                        ],
                        [
                            'id' => 2,
                            'name' => 'child',
                            'parentid' => 1,
                            'ext' => '',
                        ],
                    ],
                ]
            )
        ;
        $this->department->expects('get')
            ->with(1)
            ->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'id' => 1,
                    'name' => 'root',
                    'parentid' => 0,
                    'outerDept' => true,
                    'deptManagerUseridList' => 'manager1122|manager3211',
                    'sourceIdentifier' => 'source',
                    'ext' => 'blah',
                ]
            )
        ;
        $this->department->expects('get')
            ->with(2)
            ->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'id' => 2,
                    'name' => 'child',
                    'parentid' => 1,
                    'outerDept' => false,
                    'ext' => '',
                ]
            )
        ;

        $dept1 = new Department();
        $dept1->id = 1;
        $dept1->name = 'root';
        $dept1->parentId = 0;
        $dept1->isOuterDepartment = true;
        $dept1->ext = 'blah';
        $dept1->deptManagerUseridList = 'manager1122|manager3211';
        $dept1->sourceIdentifier = 'source';

        $dept2 = new Department();
        $dept2->id = 2;
        $dept2->name = 'child';
        $dept2->parentId = 1;
        $dept2->isOuterDepartment = false;

        $dept1->children = [$dept2];
        self::assertEquals($dept1, $this->dd->getDepartmentTree());

        // cached
        self::assertEquals($dept1, $this->dd->getDepartmentTree());

        self::assertEquals($dept1, $this->dd->getDepartment(1));
        self::assertEquals($dept2, $this->dd->getDepartment(2));
    }
}
