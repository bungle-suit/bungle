<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterSTTPass implements CompilerPassInterface
{
    public const STT_TAG = 'bungle.stt';

    public function process(ContainerBuilder $container)
    {
        $dispatcher = $container->findDefinition('event_dispatcher');
        foreach ($container->findTaggedServiceIds(self::STT_TAG, true) as $id => $stt) {
            $high = self::getHigh($container, $id);
            $dispatcher->addMethodCall('addListener', [
              "workflow.$high.transition",
              [new ServiceClosureArgument(new Reference($id)), '__invoke'],
              0,
            ]);
        }
    }

    private static function getHigh(ContainerBuilder $container, string $id): string
    {
        $cls = $container->getDefinition($id)->getClass();

        return ($cls.'::getHigh')();
    }
}
