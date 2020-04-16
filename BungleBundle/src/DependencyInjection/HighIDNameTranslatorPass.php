<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HighIDNameTranslatorPass implements CompilerPassInterface
{
    public const IDName_TAG = 'bungle.idName';

    public function process(ContainerBuilder $container)
    {
        $refs = array_map(fn (string $id) => new Reference($id), array_keys($container->findTaggedServiceIds(self::IDName_TAG)));
        $chainDef =$container->getDefinition('bungle.high_id_name_translator_chain');

        if (null !== $chainDef) {
            $chainDef->addArgument($refs);
        }
    }
}
