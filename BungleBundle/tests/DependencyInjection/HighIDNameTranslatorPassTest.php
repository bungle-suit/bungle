<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Ent\IDName\HighIDNameTranslatorChain;
use Bungle\FrameworkBundle\DependencyInjection\HighIDNameTranslatorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HighIDNameTranslatorPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->register('Translator1', 'Translator1')->addTag(HighIDNameTranslatorPass::IDName_TAG);
        $container->register('Translator2', 'Translator2')->addTag(HighIDNameTranslatorPass::IDName_TAG);
        $container->register('bungle.id_name.chain_translator', HighIDNameTranslatorChain::class)
            ->addArgument(new Reference('bungle.entity.registry'));

        (new HighIDNameTranslatorPass())->process($container);
        $def = $container->getDefinition('bungle.id_name.chain_translator');
        self::assertCount(2, $def->getArguments());
        self::assertEquals([
            new Reference('Translator1'),
            new Reference('Translator2'),
        ], $def->getArguments()[1]);
    }
}
