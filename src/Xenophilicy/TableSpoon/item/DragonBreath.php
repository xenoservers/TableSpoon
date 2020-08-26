<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\item\Item;

/**
 * Class DragonBreath
 * @package Xenophilicy\TableSpoon\item
 */
class DragonBreath extends Item {
    /**
     * DragonBreath constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct(Item::DRAGON_BREATH, $meta, "Dragon Breath");
    }
}