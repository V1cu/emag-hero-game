<?php

namespace Game\Helpers;

/**
 * Class StatsHelper
 *
 * @package Game\Helpers
 */
class StatsHelper
{
    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return int
     * @throws \Exception
     */
    public static function prepareValue($min, $max = null): int
    {
        if ( !is_numeric($min) || $min < 0 ) {
            throw new \Exception('Statistics must contain only positive numbers!');
        }

        if ( empty($max) || !is_numeric($max) || $max <= $min ) {
            return intval($min);
        }

        return rand($min, $max);
    }

    /**
     * @param  int|float  $val
     *
     * @return bool
     */
    public static function hasLuck($val): bool
    {
        return $val >= rand(0, 100);
    }
}
