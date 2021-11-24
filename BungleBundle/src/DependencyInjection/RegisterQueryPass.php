<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Bungle\Framework\Inquiry\QueryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterQueryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $factoryDef = $container->findDefinition(QueryFactory::class);
        foreach (
            $container->findTaggedServiceIds(
                QueryFactory::SERVICE_TAG,
                true
            ) as $id => $stt
        ) {
            $exporterDef = $container->getDefinition($id);
            $factoryDef->addMethodCall('addQuery', [$exporterDef]);
        }
    }
}
