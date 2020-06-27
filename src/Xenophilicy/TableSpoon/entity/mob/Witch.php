<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;

/**
 * Class Witch
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Witch extends Monster {
    
    public const NETWORK_ID = self::WITCH;
    
    public $width = 0.6;
    public $height = 1.95;
    
    public function getName(): string{
        return "Witch";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(26);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return [];
    }
}
