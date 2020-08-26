<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;


use pocketmine\item\Item;

/**
 * Class Lead
 * @package Xenophilicy\TableSpoon\item
 */
class Lead extends Item {
    /**
     * Lead constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(self::LEAD, $meta, "Lead");
    }
}