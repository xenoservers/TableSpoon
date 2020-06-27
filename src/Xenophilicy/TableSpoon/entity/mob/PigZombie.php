<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\item\Item;

/**
 * Class PigZombie
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class PigZombie extends Undead {
    
    public const NETWORK_ID = self::ZOMBIE_PIGMAN;
    
    public $width = 0.6;
    public $height = 1.95;
    
    public function getName(): string{
        return "Zombie Pigman";
    }
    
    public function getDrops(): array{
        $drops = [Item::get(Item::GOLD_NUGGET, 0, mt_rand(0, 1)), Item::get(Item::ROTTEN_FLESH, 0, mt_rand(0, 1)),];
        
        if(mt_rand(1, 200) <= 7){
            $drops[] = Item::get(Item::GOLD_INGOT, 0, 1);
        }
        return $drops;
    }
}