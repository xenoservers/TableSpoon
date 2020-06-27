<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;

/**
 * Class Wolf
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Wolf extends Animal {
    
    public const NETWORK_ID = self::WOLF;
    
    public $width = 0.6;
    public $height = 0.85;
    
    public function getName(): string{
        return "Wolf";
    }
}