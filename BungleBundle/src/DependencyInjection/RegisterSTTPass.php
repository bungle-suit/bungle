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
            $hook = function (string $evt, string $method) use ($dispatcher, $id) {
                $dispatcher->addMethodCall('addListener', [
                  $evt,
                  [new ServiceClosureArgument(new Reference($id)), $method],
                  0,
                ]);
            };

            $high = self::getHigh($container, $id);
            $hook("workflow.$high.transition", '__invoke');
        }
    }

    private static function getHigh(ContainerBuilder $container, string $id): string
    {
        $cls = $container->getDefinition($id)->getClass();

        return ($cls.'::getHigh')();
    }
}
