<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests;

use Bungle\DingTalk\DingTalk;
use Bungle\DingTalk\DingTalkException;
use Bungle\DingTalk\Hydrate\Department;
use Bungle\DingTalk\Hydrate\User;
use EasyDingTalk\Application;
use EasyDingTalk\Department\Client as DepartmentClient;
use EasyDingTalk\User\Client as UserClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class DingTalkTest extends MockeryTestCase
{
    /** @var Application|Mockery\LegacyMockInterface|Mockery\MockInterface  */
    private $app;
    private DingTalk $dd;
    /** @var DepartmentClient|Mockery\LegacyMockInterface|Mockery\MockInterface  */
    private $department;
    /** @var UserClient|Mockery\LegacyMockInterface|Mockery\MockInterface  */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Mockery::mock(DepartmentClient::class);
        $this->user = Mockery::mock(UserClient::class);
        $this->app = Mockery::mock(Application::class);
        $this->app->department = $this->department;
        $this->app->user = $this->user;
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

    public function testGetDepartment(): DingTalk
    {
        $this->mockDepartments();

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
        return $this->dd;
    }

    public function testGetUsers(): void
    {
        $this->mockDepartments();
        $recZhangsan = [
            'userid' => 'zhangsan',
            'unionid' => 'PiiiPyQqBNBii0HnCJ3zljcxxxxxx',
            'mobile' => '13212345678',
            'isLeaderInDepts' => [],
            'order' => 1,
            'name' => '张三',

            'active' => true,
            'department' => [1],
            'position' => '工程师',
            'email' => 'test@xxx.com',
        ];
        $recLisi = [
            'userid' => 'lisi',
            'unionid' => 'PiiiPyQqBNBii0HnCJ3zljcxxxxx1',
            'mobile' => '13212345679',
            'order' => 1,
            'name' => '莉丝',
            'active' => true,
            'department' => [1, 2],
            'position' => '工程师',
            'email' => 'test@xxx.com',
        ];
        $this->user->expects('getDetailedUsers')
            ->with(1, 0, 100)->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'userlist' => [ $recZhangsan, $recLisi ],
                ]
            );
        $this->user->expects('getDetailedUsers')
            ->with(2, 0, 100)->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'userlist' => [ $recLisi ],
                ]
            );

        $u1= new User();
        $u1->id = 'zhangsan';
        $u1->name = '张三';
        $u1->mobile = '13212345678';
        $u1->active = true;
        $u1->departments = [1];

        $u2= new User();
        $u2->id = 'lisi';
        $u2->name = '莉丝';
        $u2->mobile = '13212345679';
        $u2->active = true;
        $u2->departments = [1, 2];
        $exp = [
            'zhangsan' => $u1,
            'lisi' => $u2,
        ];

        self::assertEquals($exp, $this->dd->getUsers());
    }

    private function mockDepartments(): void
    {
        $this
            ->department
            ->expects('list')
            ->with()
            ->andReturn(
                [
                    'errcode' => 0,
                    'errmsg' => 'ok',
                    'department' => [
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
            );
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
            );
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
            );
    }
}
