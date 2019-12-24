<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Define high prefix of a entity class.
 *
 * @Annotation
 * @Target("CLASS")
 */
final class HighPrefix
{
    /**
     * @Required
     */
    public string $value;

    /**
     * Resolve high prefix for the specific class.
     *
     * Returns null if the Annotation not defined.
     */
    public static function loadHighPrefix(string $clsName): ?string
    {
        /*
         * Doctrine annotations lib will failed if some annotations class not loaded,
         */
        require_once __DIR__.'/LogicName.php';

        $cls = new \ReflectionClass($clsName);
        $reader = new AnnotationReader();
        $anno = $reader->getClassAnnotation($cls, HighPrefix::class);
        if (!$anno) {
            return null;
        }

        $high = $anno->value;
        if (preg_match('/^[a-z]{3}$/', $high) === 0) {
            throw new \UnexpectedValueException("Invalid format of high value '$high' of Entity '$clsName'");
        }
        return $high;
    }
}
