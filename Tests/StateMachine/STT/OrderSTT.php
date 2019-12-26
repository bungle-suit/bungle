<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine\STT;

use Bungle\FrameworkBundle\StateMachine\STTInterface;
use Bungle\FrameworkBundle\StateMachine\StepContext;
use Bungle\FrameworkBundle\Tests\StateMachine\Entity\Order;

class OrderSTT implements STTInterface
{
    public static function setCodeFoo(Order $ord): void
    {
        $ord->code = 'foo';
    }

    public static function setCodeBar(Order $ord): void
    {
        $ord->code = 'bar';
    }

    public function abort(Order $ord): string
    {
        return 'Abort';
    }

    public function updateCodeWithTransitionName(Order $ord, StepContext $ctx): void
    {
        $ord->code = $ctx->getTransitionName();
    }

    public static function getHighPrefix(): string
    {
        return 'ord';
    }

    public static function getActionSteps(): array
    {
        return [
          'save' => [
            [static::class, 'setCodeFoo'],
          ],
          'update' => [
            [static::class, 'updateCodeWithTransitionName'],
          ],
          'check' => [
            [static::class, 'setCodeBar'],
            [static::class, 'abort'],
            [static::class, 'setCodeFoo'],
          ],
        ];
    }
}
