<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

use Bungle\FrameworkBundle\Annotations\HighPrefix as HighPrefixAnno;

/**
 * HighPrefix service provide function related to high prefix.
 */
final class HighPrefix
{
    private array $highClsMap;

    public function __construct(EntityDiscover $discover)
    {
        $this->highClsMap = self::scanMap($discover);
    }

    /**
     * Get high prefix from clsName.
     */
    public function getHigh(string $clsName): string
    {
        $r = array_search($clsName, $this->highClsMap);
        if ($r === false) {
            throw new \InvalidArgumentException("No high prefix defined for class '$clsName'");
        }

        return $r;
    }

    /**
     * Get class full path from high prefix.
     */
    public function getClass(string $high): string
    {
        if (!isset($this->highClsMap[$high])) {
            throw new \InvalidArgumentException("Entity high '$high' not defined");
        }

        return $this->highClsMap[$high];
    }

    // returns high -> className
    private static function scanMap(EntityDiscover $discover): array
    {
        $r = [];
        foreach ($discover->getAllEntities() as $cls) {
            $r[HighPrefixAnno::resolveHighPrefix($cls)] = $cls;
        }
        return $r;
    }
}
