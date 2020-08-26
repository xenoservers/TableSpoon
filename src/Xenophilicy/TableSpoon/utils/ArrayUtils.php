<?php
declare(strict_types=1);

// This file was borrowed from Muqsit's DimensionPortals plugin

namespace Xenophilicy\TableSpoon\utils;

use Closure;

/**
 * Class ArrayUtils
 * @package Xenophilicy\TableSpoon\utils
 */
final class ArrayUtils {
    
    /**
     * @param mixed[] $array
     * @param Closure $condition
     * @param mixed|null $fallback
     * @return mixed|null
     */
    public static function firstOrDefault(array $array, Closure $condition, $fallback = null){
        foreach($array as $index => $element){
            if($condition($index, $element)){
                return $element;
            }
        }
        
        return $fallback;
    }
}