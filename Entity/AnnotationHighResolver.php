<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Entity;

use Bungle\FrameworkBundle\Annotation\HighPrefix;

class AnnotationHighResolver implements HighResolverInterface
{
    public function resolveHigh(string $entityCls): ?string
    {
        return HighPrefix::loadHighPrefix($entityCls);
    }
}
