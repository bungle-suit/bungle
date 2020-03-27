<?php

declare(strict_types=1);

namespace Bungle\Framework\Security;

use Bungle\Framework\Entity\EntityRegistry;
use Bungle\Framework\StateMachine\EntityWorkflowDefinitionResolverInterface as WorkflowResolver;
use Iterator;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\Exception\InvalidArgumentException;

final class EntityRoleDefinitionProvider implements RoleDefinitionProviderInterface
{
    private EntityRegistry $entityRegistry;
    private WorkflowResolver $workflowResolver;

    public function __construct(EntityRegistry $entityRegistry, WorkflowResolver $workflowResolver)
    {
        $this->entityRegistry = $entityRegistry;
        $this->workflowResolver = $workflowResolver;
    }

    public function getRoleDefinitions(): Iterator
    {
        foreach ($this->entityRegistry->entities as $entity) {
            $high = $this->entityRegistry->getHigh($entity);
            /** @var Definition $def */
            /** @var string[] $actionTitles */
            try {
                list($def, $actionTitles) = $this->workflowResolver->resolveDefinition($entity);
            } catch (InvalidArgumentException $e) {
                // If workflow not defined, workflow registry throws thi exception.
                continue;
            }
            
            $actions = [];
            foreach ($def->getTransitions() as $trans) {
                $action = $trans->getName();
                if (array_key_exists($action, $actions)) {
                    // If transition definition contains multiple from state, workflow
                    // parse into two Transition object with the same name.
                    //
                    // Maybe because we use StateMachine instead of Workflow, just
                    // guess.
                    continue;
                }

                $actions[$action] = true;
                yield new RoleDefinition(
                    RoleDefinition::newActionRole($high, $action),
                    $actionTitles[$action] ?? $action,
                    '',
                );
            }
        }
    }
}