<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Zombie;

/**
 * Class ZombieVillager
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class ZombieVillager extends Zombie {
    
    public const NETWORK_ID = self::ZOMBIE_VILLAGER;
    
    public $width = 0.6;
    public $height = 1.95;
    
    public function getName(): string{
        return "Zombie Villager";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(20);
        parent::initEntity();
    }
}
