<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\StateMachine;

use Symfony\Component\Workflow\Definition;

interface EntityWorkflowDefinitionResolverInterface
{
  // Resolve workflow definition by entity class.
  // @return [Definition, [TransitionName => ActionName]
    public function resolveDefinition(string $entityClass): array;
}
