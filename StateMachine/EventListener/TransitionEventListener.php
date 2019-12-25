<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\StateMachine\EventListener;

use Symfony\Component\Workflow\Event\TransitionEvent;
use Bungle\FrameworkBundle\Meta\HighPrefix;

/**
 * Host STTInterfaces, run transition steps on state
 * machine transition.
 *
 * TODO: generate class for each entity class, for better performance.
 *
 * For Entity class `Foo`, assume STT class is `..\STT\FooSTT`.
 */
final class TransitionEventListener
{
    private HighPrefix $highPrefix;
    public function __construct(HighPrefix $highPrefix)
    {
        $this->highPrefix = $highPrefix;
    }

    public function __invoke(TransitionEvent $event): void
    {
        $subject = $event->getSubject();
        $entityClass = \get_class($subject);
        $sttClass = static::getSTTClass($entityClass);
        assert(($sttClass.'::getHighPrefix')() == $this->highPrefix->getHigh($entityClass));

        $steps = $sttClass::getActionSteps()[$event->getTransition()->getName()];
        foreach ($steps as $step) {
            call_user_func($step, $subject);
        }
    }

    /**
     * Return STT class by entity class name.
     */
    public static function getSTTClass(string $entityClass): string
    {
        $words = explode('\\', $entityClass);
        $words[count($words)-1] .= 'STT';
        $words[count($words)-2] = 'STT';
        return implode('\\', $words);
    }
}
