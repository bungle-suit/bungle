<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate;

use Bungle\DingTalk\Hydrate\User;
use Bungle\DingTalk\Hydrate\UserListResponse;
use PHPUnit\Framework\TestCase;

class UserListResponseTest extends TestCase
{
    public function testHydrate(): void
    {
        $resp = [
            'errcode' => 1,
            'errmsg' => 'ok',
            'hasMore' => false,
            'userlist' => [
                [
                    'userid' => 'zhangsan',
                    'name' => '张三',
                ],
            ],
        ];

        $exp = new UserListResponse();
        $exp->errorCode = 1;
        $exp->errorMessage = 'ok';
        $u = new User();
        $u->id = 'zhangsan';
        $u->name = '张三';
        $exp->users = [$u];
        self::assertEquals($exp, UserListResponse::hydrate($resp));
    }
}
