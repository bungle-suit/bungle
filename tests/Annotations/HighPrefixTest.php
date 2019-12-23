<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Annotations\HighPrefix;
use Bungle\FrameworkBundle\Annotations\AnnotationNotDefinedException;

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
