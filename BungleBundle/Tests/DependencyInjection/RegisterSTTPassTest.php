<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Tests\StateMachine\STT\OrderSTT;
use Bungle\FrameworkBundle\DependencyInjection\RegisterSTTPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class RegisterSTTPassTest extends TestCase
{
    public function testAddEventSubscribeTags(): void
    {
        $container = new ContainerBuilder();
        $container->register(OrderSTT::class, OrderSTT::class)
            ->addTag(RegisterSTTPass::STT_TAG);
        $container->setDefinition('event_dispatcher', new Definition(EventDispatcher::class));

        (new RegisterSTTPass())->process($container);

        $dispatcher = $container->get('event_dispatcher');
        $events = $dispatcher->getListeners('workflow.ord.transition');
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderSTT::class, $events[0][0]);
        self::assertEquals('__invoke', $events[0][1]);

        $events = $dispatcher->getListeners('vina.ord.save');
        self::assertCount(1, $events);
        self::assertInstanceOf(OrderSTT::class, $events[0][0]);
        self::assertEquals('invokeSave', $events[0][1]);
    }
}
