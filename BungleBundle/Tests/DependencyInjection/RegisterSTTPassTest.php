<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Psr\EventDispatcher\EventDispatcherInterface;
use Bungle\FrameworkBundle\DependencyInjection\RegisterSTTPass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bungle\Framework\Tests\StateMachine\STT\OrderSTT;

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
    }
}
