<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine\EventListener;

use Bungle\FrameworkBundle\Meta\HighPrefix;
use Bungle\FrameworkBundle\Meta\SimpleEntityDiscover;
use Bungle\FrameworkBundle\StateMachine\Entity;
use Bungle\FrameworkBundle\StateMachine\MarkingStore\PropertyMarkingStore;
use Bungle\FrameworkBundle\StateMachine\EventListener\TransitionEventListener;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Order;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TransitionEventListenerTest extends TestCase
{
    public function testGetSTTClass(): void
    {
        $f = TransitionEventListener::class.'::getSTTClass';

        self::assertEquals('STT\FooSTT', $f('Entity\Foo'));
        self::assertEquals('Order\STT\BarSTT', $f('Order\Entity\Bar'));
    }

    public function testInvoke(): void
    {
        $dispatcher = new EventDispatcher();
        $listener = new TransitionEventListener(
            new HighPrefix(
                new SimpleEntityDiscover([Order::class])
            )
        );
        $dispatcher->addListener('workflow.transition', $listener);
        $workflow = self::createOrderWorkflow($dispatcher);
        $ord = new Order();
        $workflow->apply($ord, 'save');

        self::assertEquals('foo', $ord->code);
    }

    private static function createOrderWorkflow(EventDispatcher $dispatcher): Workflow
    {
        $definitionBuilder = new DefinitionBuilder();
        $definition = $definitionBuilder->addPlaces([
        Entity::INITIAL_STATE, 'saved'])
                                      ->addTransition(new Transition('save', Entity::INITIAL_STATE, 'saved'))
                                      ->build();

        $marking = new PropertyMarkingStore('state');
        return new Workflow($definition, $marking, $dispatcher);
    }
}
