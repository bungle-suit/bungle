<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Form\BungleFormTypeGuesser;
use Bungle\FrameworkBundle\DependencyInjection\DisableFormGuesser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\FormTypeGuesserChain;

class DisableFormGuesserTest extends TestCase
{
    const TAG_TYPE_GUESSER = 'form.type_guesser';
    private static function newGuessService(ContainerBuilder $container, string $id, string $class): Definition
    {
        $def = new Definition($class);
        $def->addTag(self::TAG_TYPE_GUESSER);
        $def->addTag('other_tag');
        $container->setDefinition($id, $def);
        return $def;
    }

    public function testProcess(): void
    {
        list($id1, $id2)  = ['guess.id1', 'guess.id2'];
        $container = new ContainerBuilder();
        self::newGuessService($container, $id1, 'validatorGuess');
        self::newGuessService($container, $id2, 'odmGuess');

        $chained = new Definition(FormTypeGuesserChain::class);
        $chained->addArgument([ new Reference($id1), new Reference($id2) ]);

        (new DisableFormGuesser())->process($container);
        self::assertFalse($container->findDefinition($id1)->hasTag(self::TAG_TYPE_GUESSER));
        self::assertTrue($container->findDefinition($id1)->hasTag('other_tag'));
        self::assertFalse($container->findDefinition($id2)->hasTag(self::TAG_TYPE_GUESSER));
        self::assertTrue($container->findDefinition($id2)->hasTag('other_tag'));

        self::assertEquals($chained, $container->findDefinition('bungle.type_guesser.chained'));

        $exp = new Definition(BungleFormTypeGuesser::class);
        $exp->addTag(self::TAG_TYPE_GUESSER);
        $exp->addArgument(new Reference('bungle.type_guesser.chained'));
        $exp->addArgument(new Reference('property_info'));
        $exp->addArgument(new Reference('Psr\Log\LoggerInterface'));
        self::assertEquals($exp, $container->findDefinition('bungle.form.type_guesser'));
    }
}
