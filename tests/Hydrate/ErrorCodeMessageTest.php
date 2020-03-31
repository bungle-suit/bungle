<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Tests\Hydrate;

use Bungle\DingTalk\Hydrate\ErrorCodeMessage;
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
        $this->expectExceptionMessage('10/Bad Happened');

        /** @var Mock|ErrorCodeMessage $rec */
        $rec = Mockery::mock(ErrorCodeMessage::class)->makePartial();
        $rec->errorCode = 10;
        $rec->errorMessage = 'Bad Happened';
        $rec->checkCode();
    }
}
