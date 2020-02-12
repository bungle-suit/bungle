<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityMeta;
use Bungle\FrameworkBundle\Entity\EntityPropertyMeta;

final class EntityMetaTest extends TestCase
{
    public function testName(): void
    {
        $meta = new EntityMeta(self::class, 'foobar', []);
        self::assertEquals('EntityMetaTest', $meta->name());
    }
}
