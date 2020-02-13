<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Entity\EntityRegistry;
use Bungle\FrameworkBundle\Entity\ArrayEntityDiscovery;
use Bungle\FrameworkBundle\Entity\ArrayHighResolver;
use Bungle\FrameworkBundle\Security\EntityRoleDefinitionProvider;
use Bungle\FrameworkBundle\Security\RoleDefinition;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Order;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Product;
use Bungle\FrameworkBundle\StateMachine\ArrayEntityWorkflowDefinitionResolver as ArrayWorkflowResolver;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\Transition;

final class EntityRoleDefinitionProviderTest extends TestCase
{
    public function testRoles(): void
    {
        $orderDef = new Definition([
        'new', 'saved', 'checked', 'deleted'
        ], [
        new Transition('save', 'new', 'saved'),
        new Transition('check', 'saved', 'checked'),
        new Transition('rollback', 'checked', 'saved'),
        new Transition('delete', 'saved', 'deleted'),
        ]);
        $productDef = new Definition([
        'new', 'saved', 'checked', 'disabled'
        ], [
        new Transition('save', 'new', 'saved'),
        new Transition('check', 'saved', 'checked'),
        new Transition('rollback', 'checked', 'saved'),
        new Transition('disable', 'checked', 'disabled'),
        new Transition('enable', 'disabled', 'checked'),
        ]);

        $workflowResolver = new ArrayWorkflowResolver([
        Order::class => [
        $orderDef,
        ['save' => 'Save', 'check' => 'Check'],
        ],
        Product::class => [$productDef, []]
        ]);
        $entityReg = new EntityRegistry(
            new ArrayEntityDiscovery([
            Order::class,
            Product::class,
            ]),
            new ArrayHighResolver([
            Order::class => 'ord',
            Product::class => 'prd',
            ])
        );
        $entityRoleDefProvider = new EntityRoleDefinitionProvider($entityReg, $workflowResolver);

        self::assertEquals([
        new RoleDefinition('ROLE_ord_save', 'Save', ''),
        new RoleDefinition('ROLE_ord_check', 'Check', ''),
        new RoleDefinition('ROLE_ord_rollback', 'rollback', ''),
        new RoleDefinition('ROLE_ord_delete', 'delete', ''),
        new RoleDefinition('ROLE_prd_save', 'save', ''),
        new RoleDefinition('ROLE_prd_check', 'check', ''),
        new RoleDefinition('ROLE_prd_rollback', 'rollback', ''),
        new RoleDefinition('ROLE_prd_disable', 'disable', ''),
        new RoleDefinition('ROLE_prd_enable', 'enable', ''),
        ], \iterator_to_array($entityRoleDefProvider->getRoleDefinitions()));
    }
}
