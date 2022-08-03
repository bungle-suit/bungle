<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests;

use Bungle\Framework\Filters;
use DateTime;
use DateTimeInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use stdClass;

class FiltersTest extends MockeryTestCase
{
    /** @dataProvider isInstanceOfProvider */
    public function testIsInstanceOf($exp, $type, $obj): void
    {
        $f = Filters::isInstanceOf($type);
        $this->assertSame($exp, $f($obj));
    }

    public function isInstanceOfProvider()
    {
        return [
            'stdClass' => [true, 'stdClass', new stdClass()],
            'not match' => [false, 'stdClass', new DateTime()],
            'interface' => [true, DateTimeInterface::class, new DateTime()],
            'base class' => [true, MockeryTestCase::class, $this],
        ];
    }
}
