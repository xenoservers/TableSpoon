<?php


namespace Xenophilicy\TableSpoon\item;


use pocketmine\item\Item;

/**
 * Class EnchantedBook
 * @package Xenophilicy\TableSpoon\item
 */
class EnchantedBook extends Item {
    /**
     * EnchantedBook constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(self::ENCHANTED_BOOK, $meta, "Enchanted Book");
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
}