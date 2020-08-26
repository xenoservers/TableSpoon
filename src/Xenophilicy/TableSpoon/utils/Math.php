<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;


use Xenophilicy\TableSpoon\Utils;

/**
 * Class Math
 * @package Xenophilicy\TableSpoon\utils
 */
class Math extends Utils {
    /**
     * @param float $min
     * @param float $max
     * @return float|int
     */
    public static function getPercentage(float $min, float $max){
        return ((min($min, $max) / max($min, $max)) * 100);
    }
    
    /**
     * @param $value
     * @param $min
     * @param $max
     * @return mixed
     */
    public static function clamp($value, $min, $max){
        return $value < $min ? $min : ($value > $max ? $max : $value);
    }
    
    /**
     * @param float $d0
     * @param $d1
     * @return float|int
     */
    public static function getDirection(float $d0, $d1){
        if($d0 < 0){
            $d0 = -$d0;
        }
        
        if($d1 < 0){
            $d1 = -$d1;
        }
        return $d0 > $d1 ? $d0 : $d1;
    }
    
    /**
     * @param float $yaw
     * @return float
     */
    public static function wrapDegrees(float $yaw){
        $yaw %= 360.0;
        if($yaw >= 180.0){
            $yaw -= 360.0;
        }
        if($yaw < -180.0){
            $yaw += 360.0;
        }
        return $yaw;
    }
}