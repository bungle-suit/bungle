<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\FrameworkBundle\DependencyInjection\BungleFrameworkExtension;
use Bungle\FrameworkBundle\Meta\HighPrefix;
use Bungle\FrameworkBundle\Meta\LogicName;
use Bungle\FrameworkBundle\StateMachine\EventListener\TransitionEventListener;
use Bungle\FrameworkBundle\StateMachine\EventListener\TransitionRoleGuardListener;
use Bungle\FrameworkBundle\Tests\StateMachine\EventListener\FakeAuthorizationChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BungleFrameworkExtensionTest extends TestCase
{
    private ContainerBuilder $container;

    public function setUp(): void
    {
        $this->container = new ContainerBuilder();
        (new BungleFrameworkExtension())->load([], $this->container);
    }

    public function testLoadHighPrefixLogicName(): void
    {
        $container = $this->container;
        self::assertTrue($container->has('bungle.framework.logic_name'));
        self::assertTrue($container->has('bungle.framework.high_prefix'));

        $logicName = $container->get('bungle.framework.logic_name');
        self::assertInstanceOf(LogicName::class, $logicName);
        self::assertSame($logicName, $container->get(LogicName::class));

        $highPrefix = $container->get('bungle.framework.high_prefix');
        self::assertInstanceOf(HighPrefix::class, $highPrefix);
        self::assertSame($highPrefix, $container->get(HighPrefix::class));
    }

    public function testStateMachine(): void
    {
        $container = $this->container;
      
        $listener = $container->get('bungle.framework.state_machine.transition_event_listener');
        self::assertInstanceOf(TransitionEventListener::class, $listener);

        $container->set('security.authorization_checker', new FakeAuthorizationChecker('Role_ADMIN'));
        $listener = $container->get('bungle.framework.state_machine.transition_role_guard_listener');
        self::assertInstanceOf(TransitionRoleGuardListener::class, $listener);
    }
}
