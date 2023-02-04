<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Export\ExcelWriter;

use Bungle\Framework\Export\ExcelWriter\MemoryLimiter;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use RuntimeException;

class MemoryLimiterTest extends MockeryTestCase
{
    public function testParseMemoryLimit(): void
    {
        self::assertIsInt(MemoryLimiter::parseMemoryLimit());
    }

    public function testNoMemoryPressure(): void
    {
        $this->expectNotToPerformAssertions();

        $limit = new MemoryLimiter();
        $limit->check();
    }

    public function testMemoryNotEnough(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('导出数据太多，内存不足');

        $limit = new MemoryLimiter(1024);
        $limit->check();
    }
}
