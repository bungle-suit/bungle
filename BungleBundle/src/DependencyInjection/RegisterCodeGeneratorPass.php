<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Bungle\Framework\Ent\Code\CodeGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register service tagged with 'bungle.codegen' into CodeGenerator service.
 */
class RegisterCodeGeneratorPass implements CompilerPassInterface
{
    public const CODE_GEN_TAG = 'bungle.codegen';

    public function process(ContainerBuilder $container)
    {
        $refs = array_map(
            fn (string $id) => new Reference($id),
            array_keys($container->findTaggedServiceIds(self::CODE_GEN_TAG))
        );
        $def = $container->getDefinition(CodeGenerator::class);
        $def->setArgument(0, $refs);
    }
}
