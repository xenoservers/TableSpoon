<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\item\Item;

/**
 * Class EyeOfEnder
 * @package Xenophilicy\TableSpoon\item
 */
class EyeOfEnder extends Item {
    /**
     * EyeOfEnder constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct(self::ENDER_EYE, $meta, "Eye Of Ender");
    }
}