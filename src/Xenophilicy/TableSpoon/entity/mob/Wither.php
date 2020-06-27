<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class Wither
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Wither extends Animal {
    
    public const NETWORK_ID = self::WITHER;
    
    public $width = 0.9;
    public $height = 3.5;
    
    public function getName(): string{
        return "Wither";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(300);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return [Item::get(Item::NETHER_STAR, 0, 1),];
    }
}
