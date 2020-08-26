<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\tile;

use pocketmine\tile\Tile as PMTile;
use ReflectionException;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class Tile
 * @package Xenophilicy\TableSpoon\tile
 */
abstract class Tile extends PMTile {
    /** @var string */
    public const
      BEACON = "Beacon", SHULKER_BOX = "ShulkerBox", HOPPER = "Hopper", JUKEBOX = "Jukebox", CAULDRON = "Cauldron";
    
    public static function init(){
        try{
            self::registerTile(Beacon::class);
            self::registerTile(ShulkerBox::class);
            self::registerTile(Hopper::class);
            self::registerTile(BrewingStand::class);
            self::registerTile(Cauldron::class);
            
            //self::registerTile(Jukebox::class);
        }catch(ReflectionException $e){
            TableSpoon::getInstance()->getLogger()->error($e); // stfu phpstorm
        }
    }
}
