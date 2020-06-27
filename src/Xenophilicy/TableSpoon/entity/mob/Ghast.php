<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class Ghast
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Ghast extends Animal {
    
    public const NETWORK_ID = self::GHAST;
    
    public $width = 6;
    public $length = 6;
    public $height = 6;
    
    public function getName(): string{
        return "Ghast";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(10);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        if(mt_rand(0, 1) == 1){
            $drops = [Item::get(Item::GUNPOWDER, 0, mt_rand(0, 1)),];
        }else{
            $drops = [Item::get(Item::GHAST_TEAR, 0, 1),];
        }
        return $drops;
    }
}
