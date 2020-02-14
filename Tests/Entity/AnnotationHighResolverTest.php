<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\Framework\Entity\AnnotationHighResolver;
use Bungle\Framework\Tests\Annotations\Entity;

final class AnnotationHighResolverTest extends TestCase
{
    public function test(): void
    {
        $resolver = new AnnotationHighResolver();
        self::assertEquals('ent', $resolver->resolveHigh(Entity::class));
        self::assertNull($resolver->resolveHigh(self::class));
    }
}
