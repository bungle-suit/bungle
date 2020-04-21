<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HighIDNameTranslatorPass implements CompilerPassInterface
{
    public const ID_NAME_TAG = 'bungle.idName';

    public function process(ContainerBuilder $container)
    {
        $refs = array_map(
            fn (string $id) => new Reference($id),
            array_keys($container->findTaggedServiceIds(self::ID_NAME_TAG))
        );
        $chainDef =$container->getDefinition('bungle.id_name.chain_translator');

        if (null !== $chainDef) {
            $chainDef->addArgument($refs);
        }
    }
}
