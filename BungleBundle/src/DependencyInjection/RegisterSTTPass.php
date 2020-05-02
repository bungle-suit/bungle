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
        /** @var string[] $sttByHigh */
        $sttByHigh = [];
        $dispatcher = $container->findDefinition('event_dispatcher');
        foreach ($container->findTaggedServiceIds(self::STT_TAG, true) as $id => $stt) {
            $hook = function (string $evt, string $method) use ($dispatcher, $id) {
                $dispatcher->addMethodCall('addListener', [
                  $evt,
                  [new ServiceClosureArgument(new Reference($id)), $method],
                  0,
                ]);
            };

            $sttClass = self::getSTTClass($container, $id);
            $high = self::getHigh($sttClass);
            $hook("workflow.$high.transition", '__invoke');

            $sttByHigh[$high] = $sttClass;
        }

        $locator = $container->getDefinition('bungle.state_machine.stt_locator');
        $locator->setArgument(2, $sttByHigh);
    }

    private static function getSTTClass(ContainerBuilder $container, string $id): string
    {
        return $container->getDefinition($id)->getClass();
    }

    private static function getHigh(string $sttClass): string
    {
        return ($sttClass.'::getHigh')();
    }
}
