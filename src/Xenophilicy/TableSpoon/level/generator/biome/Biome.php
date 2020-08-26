<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\generator\biome;

use pocketmine\level\biome\HellBiome;
use Xenophilicy\TableSpoon\level\generator\ender\biome\EnderBiome;

/**
 * Class Biome
 * @package Xenophilicy\TableSpoon\level\generator\biome
 */
abstract class Biome extends \pocketmine\level\biome\Biome {
    
    /** @var int */
    public const
      END = 9, FROZEN_OCEAN = 10, FROZEN_RIVER = 11,
      
      ICE_MOUNTAINS = 13, MUSHROOM_ISLAND = 14, MUSHROOM_ISLAND_SHORE = 15, BEACH = 16, DESERT_HILLS = 17, FOREST_HILLS = 18, TAIGA_HILLS = 19,
      
      BIRCH_FOREST_HILLS = 28, ROOFED_FOREST = 29, COLD_TAIGA = 30, COLD_TAIGA_HILLS = 31, MEGA_TAIGA = 32, MEGA_TAIGA_HILLS = 33, EXTREME_HILLS_PLUS = 34, SAVANNA = 35, SAVANNA_PLATEAU = 36, MESA = 37, MESA_PLATEAU_F = 38, MESA_PLATEAU = 39,
      
      VOID = 127;
    
    public static function init(){
        parent::init();
        
        self::register(self::HELL, new HellBiome());
        self::register(self::END, new EnderBiome());
        // TODO: ADD Other Biomes
    }
}
