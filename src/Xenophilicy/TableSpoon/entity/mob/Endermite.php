<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;

/**
 * Class Endermite
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Endermite extends Monster {
    
    public const NETWORK_ID = self::ENDERMITE;
    
    public $height = 0.3;
    public $width = 0.4;
    
    public function getName(): string{
        return "Endermite";
    }
}