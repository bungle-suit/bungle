<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Order;
use Bungle\FrameworkBundle\Entity\EntityUtils;
use Bungle\FrameworkBundle\Exception\Exceptions;

class EntityCtorHasArgumnet
{
    public function __construct(int $id)
    {
    }
}

final class EntityUtilsTest extends TestCase
{
    public function testCreate(): void
    {
        self::assertInstanceOf(Order::class, EntityUtils::create(Order::class));
    }

    public function testCreateFailedCtorHasArguments(): void
    {
        $this->expectException(\DomainException::class);
        EntityUtils::create(EntityCtorHasArgumnet::class);
    }
}
