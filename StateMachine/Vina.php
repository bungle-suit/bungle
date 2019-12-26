<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\StateMachine;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Registry;

/**
 * Vina is a service help us to handle StateMachine
 * common operations.
 *
 * The name Vina considered to be the name of AI
 * Assistant.
 */
class Vina
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Returns associated array of transition name -> title
     * for StateMachine attached with $subject.
     */
    public function getTransitionTitles($subject): array
    {
        $sm = $this->registry->get($subject);
        $store = $sm->getMetadataStore();
        $r = [];
        foreach ($sm->getDefinition()->getTransitions() as $trans) {
            $meta = $store->getTransitionMetadata($trans);
            $r[$trans->getName()] = $meta['title'] ?? $trans->getName();
        }
        return $r;
    }
}
