<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Test\Helpers;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Helpers\DebugHelper;

final class DebugHelperTest extends TestCase
{
    public function testIsDebug(): void
    {
        self::assertTrue(DebugHelper::isDebug());

        $_SERVER['APP_DEBUG'] = '0';
        self::assertTrue(DebugHelper::isDebug()); // isDebug caches result.
        self::assertFalse(DebugHelper::resolveIsDebug());

        $_SERVER['APP_DEBUG'] = '1';
        self::assertTrue(DebugHelper::resolveIsDebug());
    }
}
