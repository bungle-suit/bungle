<?php
declare(strict_types=1);

namespace Bungle\Framework\Model;

final class ArrayUtil
{
    /**
     * Insert item or items to array at the specific $key position.
     * @param mixed[] $array
     * @param string|int $key
     * @param mixed $item if it is an array, items are insert, or the single item will be insert.
     * To insert an array as item, wrap it with '[]'.
     */
    public static function insertAt(array &$array, $key, $item): void
    {
        if (is_int($key)) {
            array_splice($array, $key, 0, $item);
        } else {
            $pos = array_search($key, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $item,
                array_slice($array, $pos)
            );
        }
    }
}
