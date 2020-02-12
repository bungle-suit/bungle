<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Exception;

class EntityNotFoundException extends \LogicException
{
    public static function entityClass(string $entityClass): EntityNotFoundException
    {
        return new EntityNotFoundException("Entity class $entityClass not found");
    }
}
