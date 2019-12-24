<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\DependencyInjection\BungleFrameworkExtension;
use Bungle\FrameworkBundle\Meta\LogicName;
use Bungle\FrameworkBundle\Meta\HighPrefix;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BungleFrameworkExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        (new BungleFrameworkExtension())->load([], $container);

        self::assertTrue($container->has('bungle.framework.logic_name'));
        self::assertTrue($container->has('bungle.framework.high_prefix'));

        $logicName = $container->get('bungle.framework.logic_name');
        self::assertInstanceOf(LogicName::class, $logicName);
        self::assertSame($logicName, $container->get(LogicName::class));

        $highPrefix = $container->get('bungle.framework.high_prefix');
        self::assertInstanceOf(HighPrefix::class, $highPrefix);
        self::assertSame($highPrefix, $container->get(HighPrefix::class));
    }
}
