<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests;

use Bungle\Framework\Ent\BasalInfoService;
use Bungle\Framework\Filters;
use DateTime;
use DateTimeInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use stdClass;

class FiltersTest extends MockeryTestCase
{
    private BasalInfoService&Mockery\MockInterface $basal;
    private Filters $filters;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basal = Mockery::mock(BasalInfoService::class);
        $this->filters = new Filters($this->basal);
    }

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

    /** @dataProvider afterThatTimeProvider */
    public function testAfterThatTime($exp, $t): void
    {
        $this->basal->expects('now')->andReturn(new DateTime($t));
        $f = $this->filters->afterThatTime(new DateTime('2021-01-01'));
        $this->assertSame($exp, $f());
    }

    public function afterThatTimeProvider()
    {
        return [
            'before' => [false, '2020-12-31'],
            'equal' => [true, '2021-01-01'],
            'after' => [true, '2021-01-02'],
        ];
    }
}
