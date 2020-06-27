<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;

/**
 * Class Ocelot
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Ocelot extends Animal {
    
    public const NETWORK_ID = self::OCELOT;
    
    const TYPE_WILD = 0;
    const TYPE_TUXEDO = 1;
    const TYPE_TABBY = 2;
    const TYPE_SIAMESE = 3;
    
    public $width = 0.6;
    public $height = 0.7;
    
    public function getName(): string{
        return "Ocelot";
    }
}