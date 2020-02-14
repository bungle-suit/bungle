<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\StateMachine\Entity;

use Bungle\FrameworkBundle\StateMachine\Entity;
use Bungle\FrameworkBundle\Annotation\HighPrefix;

/**
 * @HighPrefix("prd")
 */
class Product extends Entity
{
    public string $code;
    public string $name;
}
