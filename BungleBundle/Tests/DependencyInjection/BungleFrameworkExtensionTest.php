<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Entity\EntityMetaRepository;
use Bungle\Framework\Entity\EntityRegistry;
use Bungle\Framework\StateMachine\EventListener\TransitionRoleGuardListener;
use Bungle\Framework\StateMachine\Vina;
use Bungle\Framework\Tests\StateMachine\EventListener\FakeAuthorizationChecker;
use Bungle\FrameworkBundle\DependencyInjection\BungleFrameworkExtension;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
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

        $this->addManagerRegistry();
        $registry = $container->get('bungle.entity.registry');
        self::assertInstanceOf(EntityRegistry::class, $registry);
        self::assertSame($registry, $container->get(EntityRegistry::class));

        $container->set('Doctrine\ODM\MongoDB\DocumentManager', $this->createStub(DocumentManager::class));
        $repository = $container->get('bungle.entity.meta_repository');
        self::assertInstanceOf(EntityMetaRepository::class, $repository);
        self::assertSame($repository, $container->get(EntityMetaRepository::class));
    }

    public function testStateMachine(): void
    {
        $container = $this->container;
        $this->addManagerRegistry();

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
        $this->addManagerRegistry();

        $reg = $this->container->get('Bungle\Framework\Security\RoleRegistry');
        self::assertEmpty($reg->defs);
    }

    private function addManagerRegistry(): ManagerRegistry
    {
        $mappingDriver = $this->createStub(MappingDriver::class);
        $mappingDriver->method('getAllClassNames')->willReturn([]);
        $config = $this->createStub(Configuration::class);
        $config->method('getMetadataDriverImpl')->willReturn($mappingDriver);
        $defManager = $this->createStub(DocumentManager::class);
        $defManager->method('getConfiguration')->willReturn($config);

        $r = $this->createStub(ManagerRegistry::class);
        $r->method('getManager')
          ->willReturn($defManager);
        $this->container->set('doctrine_mongodb', $r);

        return $r;
    }
}
