<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;

/**
 * Class Silverfish
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Silverfish extends Monster {
    
    public const NETWORK_ID = self::SILVERFISH;
    
    public $height = 0.3;
    public $width = 0.4;
    
    public function getName(): string{
        return "Silverfish";
    }
}