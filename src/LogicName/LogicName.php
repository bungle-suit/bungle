<?php

declare(strict_types=1);

namespace Mallows\Framework\LogicName;

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
     * Internal use
     */
    public static function getShortClassName(string $clsName): string
    {
        $r = strrchr($clsName, '\\');
        return $r ? substr($r, 1) : $clsName;
    }
}
