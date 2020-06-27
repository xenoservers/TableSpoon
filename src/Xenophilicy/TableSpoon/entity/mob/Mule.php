<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class Mule
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Mule extends Animal {
    
    public const NETWORK_ID = self::MULE;
    
    public $width = 1.3965;
    public $height = 1.6;
    
    public function getName(): string{
        return "Mule";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(20);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return [Item::get(Item::LEATHER, 0, mt_rand(1, 2)),];
    }
}
