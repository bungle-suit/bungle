<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Ent\BasalInfoService;
use Bungle\Framework\Ent\Code\CodeGenerator;
use Bungle\Framework\Ent\IDName\HighIDNameTranslator;
use Bungle\Framework\Ent\ObjectName;
use Bungle\Framework\Entity\EntityRegistry;
use Bungle\Framework\Export\FS;
use Bungle\Framework\Export\FSInterface;
use Bungle\Framework\Export\ParamParser\Parsers;
use Bungle\Framework\Form\PropertyInfoTypeGuesser;
use Bungle\Framework\Request\JsonRequestDataResolver;
use Bungle\Framework\Security\RoleRegistry;
use Bungle\Framework\StateMachine\EventListener\TransitionRoleGuardListener;
use Bungle\Framework\StateMachine\FSMViewVoter;
use Bungle\Framework\StateMachine\MarkingStore\StatefulInterfaceMarkingStore;
use Bungle\Framework\StateMachine\SaveSteps\ValidateSaveStep;
use Bungle\Framework\StateMachine\Steps\SetCodeStep;
use Bungle\Framework\StateMachine\Steps\ValidateStep;
use Bungle\Framework\StateMachine\STTLocator\STTLocator;
use Bungle\Framework\StateMachine\Vina;
use Bungle\Framework\Tests\StateMachine\EventListener\FakeAuthorizationChecker;
use Bungle\Framework\Twig\BungleTwigExtension;
use Bungle\FrameworkBundle\Command\ListCodeGeneratorsCommand;
use Bungle\FrameworkBundle\Command\ListIDNameCommand;
use Bungle\FrameworkBundle\DependencyInjection\BungleFrameworkExtension;
use Bungle\FrameworkBundle\DependencyInjection\DisableFormGuesser;
use Bungle\FrameworkBundle\DependencyInjection\HighIDNameTranslatorPass;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Mockery;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;

final class BungleFrameworkExtensionTest extends TestCase
{
    private ContainerBuilder $container;

    public function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->container->set('cache.app', new ArrayAdapter());
        (new BungleFrameworkExtension())->load([], $this->container);
    }

    public function testEntityServices(): void
    {
        $container = $this->container;
        self::assertTrue($container->has('bungle.entity.registry'));

        $this->addManagerRegistry();
        $registry = $container->get('bungle.entity.registry');
        self::assertInstanceOf(EntityRegistry::class, $registry);
        self::assertSame($registry, $container->get(EntityRegistry::class));
    }

    public function testObjectName(): void
    {
        $name = $this->container->get(ObjectName::class);
        self::assertInstanceOf(ObjectName::class, $name);
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
        $this->addManagerRegistry();
        $this->container->set('workflow.registry', new Registry());
        $this->container->set(
            'security.authorization_checker',
            new FakeAuthorizationChecker('Role_ADMIN'),
        );
        $this->container->set('event_dispatcher', new EventDispatcher());
        $this->container->set('request_stack', new RequestStack());

        $vina = $this->container->get('bungle.workflow.vina');
        self::assertInstanceOf(Vina::class, $vina);
        self::assertSame($vina, $this->container->get(Vina::class));
    }

    public function testRoleRegistry(): void
    {
        $this->container->set('workflow.registry', new Registry());
        $this->container->set('security.authorization_checker', new FakeAuthorizationChecker('Role_ADMIN'));
        $this->container->set('request_stack', new RequestStack());
        $this->container->set('event_dispatcher', new EventDispatcher());
        $this->addManagerRegistry();

        /** @var RoleRegistry $reg */
        $reg = $this->container->get('Bungle\Framework\Security\RoleRegistry');
        self::assertInstanceOf(RoleRegistry::class, $reg);
    }

    public function testStatefulMarkingStore(): void
    {
        $store = $this->container->get('bungle.workflow.stateful_marking_store');
        self::assertInstanceOf(StatefulInterfaceMarkingStore::class, $store);
    }

    public function testBungleTwigExtension(): void
    {
        $this->addManagerRegistry();
        $this->container->set('cache.app', new ArrayAdapter());
        (new HighIDNameTranslatorPass())->process($this->container);
        self::addManagerRegistry();

        $ext = $this->container->get('bungle.twig.extension') ;
        self::assertInstanceOf(BungleTwigExtension::class, $ext);
        $def = $this->container->getDefinition('bungle.twig.extension');
        self::assertEquals(['twig.extension' => [[]]], $def->getTags());
    }

    public function testValidationStep(): void
    {
        $this->container->set('validator', $this->createStub(ValidatorInterface::class));
        $step = $this->container->get(ValidateStep::class);
        self::assertInstanceOf(ValidateStep::class, $step);
    }

    public function testValidationSaveStep(): void
    {
        $this->container->set('validator', $this->createStub(ValidatorInterface::class));
        $step = $this->container->get(ValidateSaveStep::class);
        self::assertInstanceOf(ValidateSaveStep::class, $step);
    }

    public function testSTTLocator(): void
    {
        $this->addManagerRegistry();
        /** @var STTLocator $locator */
        $locator = $this->container->get('bungle.state_machine.stt_locator');
        self::assertInstanceOf(STTLocator::class, $locator);
    }

    public function testSTTViewVoter(): void
    {
        $this->container->set('bungle.entity.registry', Mockery::mock(EntityRegistry::class));
        $voter = $this->container->get('bungle.state_machine.stt_view_voter');
        self::assertInstanceOf(FSMViewVoter::class, $voter);

        $def = $this->container->getDefinition('bungle.state_machine.stt_view_voter');
        self::assertTrue($def->hasTag('security.voter'));
    }

    public function testHighIDNameTranslator(): void
    {
        $this->container->set('cache.app', new ArrayAdapter());
        (new HighIDNameTranslatorPass())->process($this->container);
        self::addManagerRegistry();
        $idName = $this->container->get(HighIDNameTranslator::class);
        self::assertInstanceOf(HighIDNameTranslator::class, $idName);
    }

    public function testCodeGenerator(): void
    {
        $gen = $this->container->get(CodeGenerator::class);
        self::assertInstanceOf(CodeGenerator::class, $gen);
    }

    public function testSetCodeStep(): void
    {
        $step = $this->container->get(SetCodeStep::class);
        self::assertInstanceOf(SetCodeStep::class, $step);
    }

    public function testListIDNameCommand(): void
    {
        $this->container->set('cache.app', new ArrayAdapter());
        (new HighIDNameTranslatorPass())->process($this->container);
        self::addManagerRegistry();
        $cmd = $this->container->get('bungle.command.list_id_names');
        self::assertInstanceOf(ListIDNameCommand::class, $cmd);
    }

    public function testListCodeGeneratorsCommand(): void
    {
        $cmd = $this->container->get('bungle.command.list_code_generators');
        self::assertInstanceOf(ListCodeGeneratorsCommand::class, $cmd);
    }

    public function testPropertyInfoTypeGuesser(): void
    {
        $def = $this->container->getDefinition(PropertyInfoTypeGuesser::class);
        self::assertTrue($def->hasTag(DisableFormGuesser::TAG_TYPE_GUESSER));

        $this->container->set('property_info', $this->createMock(PropertyInfoExtractorInterface::class));
        $guesser = $this->container->get(PropertyInfoTypeGuesser::class);
        self::assertInstanceOf(PropertyInfoTypeGuesser::class, $guesser);
    }

    public function testJsonRequestDataResolver(): void
    {
        $this->container->set('serializer', $this->createMock(SerializerInterface::class));
        $resolver = $this->container->get('bungle.json.request.data.resolver');
        self::assertInstanceOf(JsonRequestDataResolver::class, $resolver);

        $def = $this->container->getDefinition('bungle.json.request.data.resolver');
        self::assertEquals([['priority' => 50]], $def->getTag('controller.argument_value_resolver'));
    }

    public function testBasalInfo(): void
    {
        $this->container->set('security.helper', $this->createMock(Security::class));
        $this->container->set('doctrine.orm.default_entity_manager', $this->createMock(EntityManagerInterface::class));
        $basal = $this->container->get(BasalInfoService::class);
        self::assertInstanceOf(BasalInfoService::class, $basal);
    }

    public function testExportFSInterface(): void
    {
        $fs = $this->container->get(FSInterface::class);
        self::assertInstanceOf(FS::class, $fs);
    }

    public function testExportParsers(): void
    {
        $this->container->set('security.helper', $this->createMock(Security::class));
        $this->container->set('doctrine.orm.default_entity_manager', $this->createMock(EntityManagerInterface::class));
        $parsers = $this->container->get(Parsers::class);
        self::assertInstanceOf(Parsers::class, $parsers);
    }

    private function addManagerRegistry(): ManagerRegistry
    {
        $mappingDriver = $this->createStub(MappingDriver::class);
        $mappingDriver->method('getAllClassNames')->willReturn([]);
        $defManager = $this->createStub(ManagerRegistry::class);
        $this->container->set('doctrine', $defManager);

        /** @var Stub|ManagerRegistry $r */
        $r = $this->createStub(ManagerRegistry::class);
        $r->method('getManager')->willReturn($defManager);
        $this->container->set('doctrine_mongodb', $r);

        return $r;
    }
}
