<?php
declare(strict_types=1);

namespace Bungle\Framework\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Bungle\Framework\Tests\StateMachine\Entity\Order;
use Bungle\Framework\Entity\EntityUtils;
use Bungle\Framework\Exception\Exceptions;

class EntityCtorHasArgumnet // phpcs:ignore
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(int $id)
    {
    }
}

final class EntityUtilsTest extends TestCase // phpcs:ignore
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
