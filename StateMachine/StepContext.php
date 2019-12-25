<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\StateMachine;

use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\WorkflowInterface;

final class StepContext
{
    private $transition;
    private $workflow;

    public function __construct(Transition $transition, WorkflowInterface $workflow)
    {
        $this->transition = $transition;
        $this->workflow = $workflow;
    }

    public function getTransition(): Transition
    {
        return $this->transition;
    }

    public function getWorkflow(): WorkflowInterface
    {
        return $this->workflow;
    }
}
