<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class Parrot
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Parrot extends Animal {
    
    public const NETWORK_ID = self::PARROT;
    
    public $height = 0.9;
    public $width = 0.5;
    
    public function getName(): string{
        return "Parrot";
    }
    
    public function getDrops(): array{
        return [Item::get(Item::FEATHER, 0, mt_rand(1, 2))];
    }
}