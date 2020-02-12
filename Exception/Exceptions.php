<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Exception;

final class Exceptions
{
    private function __construct()
    {
    }

    public static function highNotDefined(string $entityClass): \DomainException
    {
        return new \DomainException("High not defined on entity '$entityClass'");
    }

    public static function highDuplicated(string $high, string $cls1, string $cls2): \DomainException
    {
        return new \DomainException("Entity class '$cls1' and '$cls2', has the same high code: $high");
    }

    public static function highNotFound(string $high): \DomainException
    {
        return new \DomainException("High not found: $high");
    }

    public static function entityNotDefined(string $entityClass): \DomainException
    {
      if (!class_exists($entityClass)) {
        return new \DomainException("No class '$entityClass', check your spell");
      }
      return new \DomainException("class '$entityClass' not declared as entity"); 
    }
}
