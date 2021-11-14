<?php

declare(strict_types=1);

namespace Helper;

use Bungle\Framework\Helper\StringUtil;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class StringUtilTest extends MockeryTestCase
{
    /** @dataProvider containsAnyProvider */
    public function testContainsAny($exp, $s, $keywords): void
    {
        self::assertEquals($exp, StringUtil::containsAny($s, $keywords));

        $f = StringUtil::newContainsAny($keywords);
        self::assertEquals($exp, $f($s));
    }

    public function containsAnyProvider()
    {
        return [
            [false, 'foo', []],
            [false, '', []],
            [true, 'foobar', ['blah', 'foo']],
        ];
    }
}
