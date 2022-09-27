<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests;

use Bungle\Framework\Converters;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ConvertersTest extends MockeryTestCase
{
    private Converters $converters;

    protected function setUp(): void
    {
        parent::setUp();

        $propAcc = new PropertyAccessor();
        $this->converters = new Converters($propAcc);
    }

    public function testAssocArrayFrom(): void
    {
        $o = new class {
            public string $f1 = 'f1';
            public string $f2 = 'f2';
            public string $f3 = 'f3';
        };

        $ret = $this->converters->assocArrayFrom(
            [
                'a' => 'f1',
                'b' => 'f2',
                'c' => static fn($x) => $x->f3,
            ],
            $o
        );

        self::assertEquals(['a' => 'f1', 'b' => 'f2', 'c' => 'f3'], $ret);
    }
}
