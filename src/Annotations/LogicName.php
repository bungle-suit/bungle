<?php

declare(strict_types=1);

namespace Bungle\Framework\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Apply logic name to class and/or property.
 *
 * @Annotation;
 * @Target({"CLASS","PROPERTY"})
 */
final class LogicName
{
    private string $name;

    public function __construct(array $args)
    {
        $this->name = $args['value'];
    }

    /**
     * resolve logic name for the specific class.
     *
     * Returns class's short name if LogicName annotation not defined.
     */
    public static function resolveClassName(string $clsName): string
    {
        $cls = new \ReflectionClass($clsName);

        $reader = new AnnotationReader();
        $anno = $reader->getClassAnnotation($cls, LogicName::class);
        return $anno ? $anno->name : self::getShortClassName($clsName);
    }

    /**
     * Resolve property logic names for the specific class.
     *
     * Returns name -> logicName array.
     *
     * Property names not marked with LogicName annotations has entry of
     * name -> name, for easier detect not-defined property.
     *
     * Include inherited properties.
     *
     * Ignores private and protected properties.
     */
    public static function resolvePropertyNames(string $clsName): array
    {
        $cls = new \ReflectionClass($clsName);
        $reader = new AnnotationReader();

        $r = [];
        foreach ($cls->getProperties(\ReflectionProperty::IS_PUBLIC) as $p) {
            if ($p->isStatic()) {
                continue;
            }
            $anno = $reader->getPropertyAnnotation($p, LogicName::class);
            $r[$p->getName()] = $anno ? $anno->name : $p->getName();
        }
        return $r;
    }

    /**
     * Internal use
     */
    public static function getShortClassName(string $clsName): string
    {
        $r = strrchr($clsName, '\\');
        return $r ? substr($r, 1) : $clsName;
    }
}
