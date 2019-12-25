<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine\Entity;

use Bungle\FrameworkBundle\Annotations\HighPrefix;
use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\StateMachine\Entity;

/**
 * @HighPrefix("ord")
 */
class Order extends Entity
{
    public string $code;
}
