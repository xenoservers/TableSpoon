<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

/**
 * Class ElderGuardian
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class ElderGuardian extends Animal {
    
    public const NETWORK_ID = self::ELDER_GUARDIAN;
    
    public $width = 1.9975;
    public $height = 1.9975;
    
    public function getName(): string{
        return "Elder Guardian";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(80);
        $this->setGenericFlag(self::DATA_FLAG_ELDER, true);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return [Item::get(Item::PRISMARINE_CRYSTALS, 0, mt_rand(0, 1)), Item::get(Item::PRISMARINE_SHARD, 0, mt_rand(0, 2)),];
    }
}
