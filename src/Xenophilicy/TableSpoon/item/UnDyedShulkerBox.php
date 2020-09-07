<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;


use pocketmine\item\Item;
use pocketmine\nbt\tag\NamedTag;

/**
 * Class UnDyedShulkerBox
 * @package Xenophilicy\TableSpoon\item
 */
class UnDyedShulkerBox extends ShulkerBox {
    
    /**
     * @param string|null $name
     * @param NamedTag|null $inventory
     */
    public function __construct(?string $name = null, ?NamedTag $inventory = null){
        if($name === null){
            $name = "Shulker Box";
        }
        Item::__construct(self::UNDYED_SHULKER_BOX, 0, $name);
        if($inventory !== null){
            $this->getNamedTag()->setTag($inventory);
        }
    }
}