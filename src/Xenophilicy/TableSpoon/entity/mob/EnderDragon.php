<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;

/**
 * Class EnderDragon
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class EnderDragon extends Monster {
    
    public const NETWORK_ID = self::ENDER_DRAGON;
    
    public function getName(): string{
        return "Ender Dragon";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(200);
        parent::initEntity();
    }
}
