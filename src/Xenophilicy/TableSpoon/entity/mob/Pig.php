<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class Pig
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Pig extends Animal {
    
    public const NETWORK_ID = self::PIG;
    
    public $width = 0.9;
    public $height = 0.9;
    
    public function getName(): string{
        return "Pig";
    }
    
    public function getDrops(): array{
        return [Item::get(Item::RAW_PORKCHOP, 0, mt_rand(1, 3)),];
    }
}