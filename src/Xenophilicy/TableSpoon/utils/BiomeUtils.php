<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;


use pocketmine\level\Level;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class BiomeUtils
 * @package Xenophilicy\TableSpoon\utils
 */
class BiomeUtils extends Utils {
    public const
      OCEAN = 0, PLAINS = 1, DESERT = 2, MOUNTAINS = 3, FOREST = 4, TAIGA = 5, SWAMP = 6, RIVER = 7, HELL = 8, END = 9, FROZEN_OCEAN = 10, FROZEN_RIVER = 11, ICE_PLAINS = 12, ICE_MOUNTAINS = 13, MUSHROOM_ISLAND = 14, MUSHROOM_ISLAND_SHORE = 15, BEACH = 16, DESERT_HILLS = 17, FOREST_HILLS = 18, TAIGA_HILLS = 19, SMALL_MOUNTAINS = 20, COLD_BEACH = 26, BIRCH_FOREST = 27, BIRCH_FOREST_HILLS = 28, ROOFED_FOREST = 29, COLD_TAIGA = 30, COLD_TAIGA_HILLS = 31, MEGA_TAIGA = 32, MEGA_TAIGA_HILLS = 33, EXTREME_HILLS_PLUS = 34, SAVANNA = 35, SAVANNA_PLATEAU = 36, MESA = 37, MESA_PLATEAU_F = 38, MESA_PLATEAU = 39;
    
    /** @var float[] */
    public const BIOME_ID_TO_TEMPERATURE = [self::OCEAN => 0.5, self::PLAINS => 0.8, self::DESERT => 2.0, self::MOUNTAINS => 0.2, self::FOREST => 0.7, self::TAIGA => 0.25, self::SWAMP => 0.8, self::RIVER => 0.5, self::HELL => 2.0, self::END => 0.5, self::FROZEN_OCEAN => 0.0, self::FROZEN_RIVER => 0.0, self::ICE_PLAINS => 0.0, self::ICE_MOUNTAINS => 0.0, self::MUSHROOM_ISLAND => 0.9, self::MUSHROOM_ISLAND_SHORE => 0.9, self::BEACH => 0.8, self::DESERT_HILLS => 2.0, self::FOREST_HILLS => 0.7, self::TAIGA_HILLS => 0.25, self::SMALL_MOUNTAINS => 20, self::COLD_BEACH => 0.05, self::BIRCH_FOREST => 0.6, self::BIRCH_FOREST_HILLS => 0.6, self::ROOFED_FOREST => 0.7, self::COLD_TAIGA => -0.5, self::COLD_TAIGA_HILLS => -0.5, self::MEGA_TAIGA => 0.3, self::MEGA_TAIGA_HILLS => 0.25, self::EXTREME_HILLS_PLUS => 0.2, self::SAVANNA => 1.2, self::SAVANNA_PLATEAU => 1.1, self::MESA => 2.0, self::MESA_PLATEAU_F => 2.0, self::MESA_PLATEAU => 2.0];
    
    public static function getTemperature(int $x, int $y, int $z, Level $level): float{
        $temp = self::getBiomeTemperature($x, $z, $level);
        $seaLevel = 64; // default sea level
        // TODO: Biome classification
        if($y > $seaLevel){
            $temp -= ($seaLevel - $y) * 0.00166666667;
        }
        return $temp;
    }
    
    public static function getBiomeTemperature(int $x, int $z, Level $level): float{
        $id = $level->getBiomeId($x, $z);
        if(isset(self::BIOME_ID_TO_TEMPERATURE[$id])){
            return self::BIOME_ID_TO_TEMPERATURE[$id];
        }
        return 0.0;
    }
    
}