<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\item\{Durable, Item};

/**
 * Class Elytra
 * @package Xenophilicy\TableSpoon\item
 */
class Elytra extends Durable {
    /**
     * Elytra constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct(Item::ELYTRA, $meta, "Elytra Wings");
    }
    
    public function getMaxDurability(): int{
        return 433;
    }
}