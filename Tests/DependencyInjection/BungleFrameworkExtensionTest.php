<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\FrameworkBundle\DependencyInjection\BungleFrameworkExtension;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\EntityMetaRepository;
use Bungle\FrameworkBundle\StateMachine\EventListener\TransitionEventListener;
use Bungle\FrameworkBundle\StateMachine\EventListener\TransitionRoleGuardListener;
use Bungle\FrameworkBundle\StateMachine\Vina;
use Bungle\FrameworkBundle\Tests\StateMachine\EventListener\FakeAuthorizationChecker;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Workflow\Registry;

final class BungleFrameworkExtensionTest extends TestCase
{
    private ContainerBuilder $container;

    public function setUp(): void
    {
        $this->container = new ContainerBuilder();
        (new BungleFrameworkExtension())->load([], $this->container);
    }

    public function testEntityServices(): void
    {
        $container = $this->container;
        self::assertTrue($container->has('bungle.entity.registry'));
        self::assertTrue($container->has('bungle.entity.meta_repository'));

        $registry = $container->get('bungle.entity.registry');
        self::assertInstanceOf(EntityRegistry::class, $registry);
        self::assertSame($registry, $container->get(EntityRegistry::class));

        $repository = $container->get('bungle.entity.meta_repository');
        self::assertInstanceOf(EntityMetaRepository::class, $repository);
        self::assertSame($repository, $container->get(EntityMetaRepository::class));
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

    public function testVina(): void
    {
        $this->container->set('workflow.registry', new Registry());
        $this->container->set(
            'security.authorization_checker',
            new FakeAuthorizationChecker('Role_ADMIN'),
        );

        $vina = $this->container->get('bungle.workflow.vina');
        self::assertInstanceOf(Vina::class, $vina);
    }

    public function testRoleRegistry(): void
    {
        $this->container->set('workflow.registry', new Registry());
        $this->container->set('security.authorization_checker', new FakeAuthorizationChecker('Role_ADMIN'));

        $reg = $this->container->get('Bungle\FrameworkBundle\Security\RoleRegistry');
        self::assertEmpty($reg->defs);
    }
}
