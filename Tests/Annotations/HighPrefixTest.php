<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Annotations;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Annotation\HighPrefix;
use Bungle\FrameworkBundle\Annotation\AnnotationNotDefinedException;

final class HighPrefixTest extends TestCase
{
    public function testLoadHighPrefix(): void
    {
        self::assertEquals('ent', HighPrefix::loadHighPrefix(Entity::class));
    }

    public function testLoadHighPrefixNotDefined(): void
    {
        self::assertNull(HighPrefix::loadHighPrefix(HighPrefixTest::class));
    }

    public function testInvalidHighPrefixFormat(): void
    {
        self::expectException(\UnexpectedValueException::class);
        
        HighPrefix::loadHighPrefix(InvalidPrefix::class);
    }
}
