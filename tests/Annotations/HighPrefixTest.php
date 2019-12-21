<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\Annotations;

use PHPUnit\Framework\TestCase;
use Bungle\Framework\Annotations\HighPrefix;
use Bungle\Framework\Annotations\AnnotationNotDefinedException;

final class HighPrefixTest extends TestCase
{
    public function testResolveHighPrefix(): void
    {
        self::assertEquals('基础实体对象', HighPrefix::resolveHighPrefix(Entity::class));
    }

    public function testResolveHighPrefixNotDefined(): void
    {
        self::expectException(AnnotationNotDefinedException::class);

        HighPrefix::resolveHighPrefix(HighPrefixTest::class);
    }
}
