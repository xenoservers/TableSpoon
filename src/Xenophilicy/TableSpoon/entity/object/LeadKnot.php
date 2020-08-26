<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\object;


use pocketmine\entity\Entity;

/**
 * Class LeadKnot
 * @package Xenophilicy\TableSpoon\entity\object
 */
class LeadKnot extends Entity {
    public function onUpdate(int $currentTick): bool{
        return false;
    }
}