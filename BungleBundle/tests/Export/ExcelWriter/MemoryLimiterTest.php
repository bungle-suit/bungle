<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Export\ExcelWriter;

use Bungle\Framework\Export\ExcelWriter\MemoryLimiter;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class MemoryLimiterTest extends MockeryTestCase
{
    public function testParseMemoryLimit(): void
    {
        self::assertIsInt(MemoryLimiter::parseMemoryLimit());
    }
}
