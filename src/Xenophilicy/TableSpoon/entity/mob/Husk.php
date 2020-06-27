<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Zombie;

/**
 * Class Husk
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Husk extends Zombie {
    
    public const NETWORK_ID = self::HUSK;
    
    public function getName(): string{
        return "Husk";
    }
}
