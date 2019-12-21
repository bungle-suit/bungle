<?php
declare(strict_types=1);

namespace Bungle\Framework\Annotations;

use Doctrine\Common\Annotations\Annotation;
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
    private string $high;

    public function __construct(array $args)
    {
        $this->high = $args['value'];
    }

    /**
     * Resolve high prefix for the specific class.
     *
     * @throws AnnotationNotDefinedException if @@HighPrefix not defined.
     */
    public static function resolveHighPrefix(string $clsName): string
    {
        /*
         * Doctrine annotations lib will failed if some annotations class not loaded,
         */
        require_once __DIR__.'/LogicName.php';

        $cls = new \ReflectionClass($clsName);
        $reader = new AnnotationReader();
        $anno = $reader->getClassAnnotation($cls, HighPrefix::class);
        if (!$anno) {
            throw new AnnotationNotDefinedException();
        }
        return $anno->high;
    }
}
