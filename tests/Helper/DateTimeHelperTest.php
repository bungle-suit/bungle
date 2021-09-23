<?php

declare(strict_types=1);

namespace Helper;

use Bungle\Framework\Helper\DateTimeHelper;
use DateTime;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class DateTimeHelperTest extends MockeryTestCase
{
    /** @dataProvider addDaysProvider */
    public function testAddDays($exp, $d, $days): void
    {
        $input = new DateTime($d);
        self::assertEquals(new DateTime($exp), $back = DateTimeHelper::addDays($input, $days));
        self::assertEquals(new DateTime($d), $input);
        self::assertNotSame($back, $input);
    }

    public function addDaysProvider()
    {
        return [
            'zero days' => ['2021-09-08', '2021-09-08', 0],
            'positive days' => ['2021-09-11', '2021-09-08', 3],
            'negative days' => ['2021-09-05', '2021-09-08', -3],
        ];
    }
}
