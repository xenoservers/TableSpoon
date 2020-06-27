<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class IronGolem
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class IronGolem extends Animal {
    
    public const NETWORK_ID = self::IRON_GOLEM;
    
    public $width = 1.4;
    public $height = 2.7;
    
    public function initEntity(): void{
        $this->setMaxHealth(100);
        parent::initEntity();
    }
    
    public function getName(): string{
        return "Iron Golem";
    }
    
    public function getDrops(): array{
        return [Item::get(Item::IRON_INGOT, 0, mt_rand(3, 5)), Item::get(Item::POPPY, 0, mt_rand(0, 2)),];
    }
}
