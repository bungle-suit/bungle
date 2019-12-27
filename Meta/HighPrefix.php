<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

use Bungle\FrameworkBundle\Annotation\HighPrefix as HighPrefixAnno;

/**
 * HighPrefix service provide function related to high prefix.
 */
final class HighPrefix
{
    private array $highClsMap;

    public function __construct(EntityLocator $discover)
    {
        $this->highClsMap = self::scanMap($discover);
        // TODO: generate source code, and load $highClsMap from php file.
    }

    /**
     * Return all high prefixes
     */
    public function getPrefixes(): array
    {
        return array_keys($this->highClsMap);
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
    private static function scanMap(EntityLocator $discover): array
    {
        $get = fn (array $a, string $k) => $a[$k]??'';

        $r = [];
        $entities = $discover->getAllEntities();
        foreach ($entities as $cls) {
            $high = HighPrefixAnno::loadHighPrefix($cls);
            if (!$high) {
                continue;
            }

            assert(
                !array_key_exists($high, $r),
                "Duplicate high '$high' defined on '$cls' and '{$get($r, $high)}",
            );
            $r[$high] = $cls;
        }
        return $r;
    }
}
