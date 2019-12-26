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
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TransitionEventListenerTest extends TestCase
{
    public function testGetSTTClass(): void
    {
        $f = TransitionEventListener::class.'::getSTTClass';

        self::assertEquals('STT\FooSTT', $f('Entity\Foo'));
        self::assertEquals('Order\STT\BarSTT', $f('Order\Entity\Bar'));
    }

    private EventDispatcher $dispatcher;
    private StateMachine $sm;
    private Order $ord;

    public function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
        $listener = new TransitionEventListener(
            new HighPrefix(
                new SimpleEntityDiscover([Order::class])
            )
        );
        $this->dispatcher->addListener('workflow.transition', $listener);
        $this->sm = self::createOrderWorkflow($this->dispatcher);
        $this->ord = new Order();
    }

    public function testInvoke(): void
    {
        $this->sm->apply($this->ord, 'save');
        self::assertEquals('foo', $this->ord->code);
        self::assertEquals('saved', $this->ord->state);
    }

    public function testInvokeWithContext(): void
    {
        $this->ord->state = 'saved';
        $this->sm->apply($this->ord, 'update');
        self::assertEquals('update', $this->ord->code);
        self::assertEquals('saved', $this->ord->state);
    }

    public function testIgnoreStepsNotConfigured(): void
    {
        self::expectWarning();
        $this->ord->state = 'saved';
        $this->sm->apply($this->ord, 'print');
        self::assertEquals('saved', $this->ord->state);
    }

    public function testThrowExceptionToAbort(): void
    {
        self::markTestSkipped('TODO');
    }

    private static function createOrderWorkflow(EventDispatcher $dispatcher): StateMachine
    {
        $definitionBuilder = new DefinitionBuilder();
        $definition = $definitionBuilder->addPlaces([
        Entity::INITIAL_STATE, 'saved'])
          ->addTransition(new Transition('save', Entity::INITIAL_STATE, 'saved'))
          ->addTransition(new Transition('update', 'saved', 'saved'))
          ->addTransition(new Transition('print', 'saved', 'saved'))
          ->build();

        $marking = new PropertyMarkingStore('state');
        return new StateMachine($definition, $marking, $dispatcher, 'ord');
    }
}
