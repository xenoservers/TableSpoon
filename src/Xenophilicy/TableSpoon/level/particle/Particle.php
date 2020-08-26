<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\particle;

use pocketmine\level\particle\Particle as PMParticle;
use pocketmine\network\mcpe\protocol\DataPacket;

/**
 * Class Particle
 * @package Xenophilicy\TableSpoon\level\particle
 */
abstract class Particle extends PMParticle {
    // Took me quite a lot of in-game crashes to test em all xD
    
    // TODO: Find the Official Names of these.
    public const
      TYPE_SMALL_SMOKE_CLOUD = 42, TYPE_FIREWORK_GREEN_OR_YELLOW = 43, // 44 crash... Test Data Value: 0
        // 45 crash... Test Data Value: 0
      TYPE_FIREWORK_WHITE = 46, TYPE_FLASH = 47;
    
    // 48-50s just crashes me... :shrug: Just add more particles here if y'all find any. :)
    
    /**
     * @return DataPacket|DataPacket[]
     */
    abstract public function encode();
}