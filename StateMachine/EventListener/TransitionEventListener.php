<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\StateMachine\EventListener;

use Symfony\Component\Workflow\Event\Event;
use Bungle\FrameworkBundle\Meta\HighPrefix;

/**
 * Host STTInterfaces, run transition steps on state
 * machine transition.
 *
 * TODO: generate class for each entity class, for better performance.
 */
final class TransitionEventListener
{
    public function __construct(HighPrefix $highPrefix)
    {
    }

    public function __invoke(Event $event): void
    {
      //
    }
}
