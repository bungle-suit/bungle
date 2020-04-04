<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate\Traits;

use Bungle\DingTalk\DingTalkException;
use Bungle\DingTalk\Hydrate\Traits\ErrorCodeMessage;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class ErrorCodeMessageTest extends TestCase
{
    public function testCheckCodeOk()
    {
        /** @var Mock|ErrorCodeMessage $rec */
        $rec = Mockery::mock(ErrorCodeMessage::class)->makePartial();
        $rec->errorCode = 0;
        $rec->errorMessage = 'OK';
        $rec->checkCode();

        self::assertTrue(true);
    }

    public function testCheckCodeFailed(): void
    {
        $this->expectException(DingTalkException::class);
        $this->expectExceptionMessage('Bad Happened');
        $this->expectExceptionCode(10);

        /** @var Mock|ErrorCodeMessage $rec */
        $rec = Mockery::mock(ErrorCodeMessage::class)->makePartial();
        $rec->errorCode = 10;
        $rec->errorMessage = 'Bad Happened';
        $rec->checkCode();
    }
}
