<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Living;

/**
 * Class MagmaCube
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class MagmaCube extends Living {
    
    public const NETWORK_ID = self::MAGMA_CUBE;
    
    public function getName(): string{
        return "Magma Cube";
    }
}