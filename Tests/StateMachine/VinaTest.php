<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine;

use Bungle\FrameworkBundle\StateMachine\Entity;
use Bungle\FrameworkBundle\StateMachine\MarkingStore\PropertyMarkingStore;
use Bungle\FrameworkBundle\StateMachine\Vina;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Order;

use PHPUnit\Framework\TestCase;

use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Metadata\InMemoryMetadataStore;

final class VinaTest extends TestCase
{
    public function testGetTransitionTitles(): void
    {
        $vina = new Vina(self::createRegistry());
        self::assertEquals([
            'save' => '保存',
            'update' => '保存',
            'print' => '打印',
            'check' => 'check',
        ], $vina->getTransitionTitles(new Order()));
    }

    public function testGetStateTitles(): void
    {
        $vina = new Vina(self::createRegistry());
        self::assertEquals([
            Entity::INITIAL_STATE => '未保存',
            'saved' => '已保存',
            'checked' => '已审核',
            'unchecked' => 'unchecked',
        ], $vina->getStateTitles(new Order()));
    }

    private static function createOrderWorkflow(): StateMachine
    {
        $trans = [
          $save = new Transition('save', Entity::INITIAL_STATE, 'saved'),
          $update = new Transition('update', 'saved', 'saved'),
          $print = new Transition('print', 'saved', 'saved'),
          $check = new Transition('check', 'saved', 'checked'),
        ];

        $transMeta = new \SplObjectStorage();
        $transMeta[$save] = ['title' => '保存'];
        $transMeta[$update] = ['title' => '保存'];
        $transMeta[$print] = ['title' => '打印'];

        $definition = (new DefinitionBuilder())
          ->setMetadataStore(new InMemoryMetadataStore(
              [],
              [
                  'saved' => ['title' => '已保存'],
                  'checked' => ['title' => '已审核'],
              ],
              $transMeta,
          ))
          ->addPlaces([Entity::INITIAL_STATE, 'saved', 'checked', 'unchecked'])
          ->addTransitions($trans)
          ->build();

        $marking = new PropertyMarkingStore('state');
        return new StateMachine($definition, $marking, null, 'ord');
    }

    private static function createRegistry(): Registry
    {
        $r = new Registry();
        $r->addWorkflow(
            self::createOrderWorkflow(),
            new InstanceOfSupportStrategy(Order::class),
        );
        return $r;
    }
}
