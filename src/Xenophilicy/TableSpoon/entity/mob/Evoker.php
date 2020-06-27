<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Evoker
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Evoker extends Monster {
    
    public const NETWORK_ID = self::EVOCATION_ILLAGER;
    
    public $width = 0.6;
    public $height = 1.95;
    
    public function getName(): string{
        return "Evoker";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(24);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return [Item::get(Item::TOTEM, 0, 1), Item::get(Item::EMERALD, 0, mt_rand(0, 1)),];
    }
}
