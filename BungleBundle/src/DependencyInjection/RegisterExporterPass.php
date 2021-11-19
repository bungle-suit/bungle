<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Bungle\Framework\Export\ExporterFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterExporterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $factoryDef = $container->findDefinition(ExporterFactory::class);
        foreach (
            $container->findTaggedServiceIds(
                ExporterFactory::SERVICE_TAG,
                true
            ) as $id => $stt
        ) {
            $exporterDef = $container->getDefinition($id);
            $factoryDef->addMethodCall('addExporter', [$exporterDef]);
        }
    }
}
