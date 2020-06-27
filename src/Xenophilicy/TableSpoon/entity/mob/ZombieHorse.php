<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\item\Item;

/**
 * Class ZombieHorse
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class ZombieHorse extends Horse {
    
    public const NETWORK_ID = self::ZOMBIE_HORSE;
    
    public function getName(): string{
        return "Zombie Horse";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(20);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return $drops = [Item::get(Item::ROTTEN_FLESH, 0, mt_rand(0, 2)),];
    }
}
