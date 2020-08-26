<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;

use pocketmine\item\Item;

/**
 * Class ArmorTypes
 * @package Xenophilicy\TableSpoon\utils
 */
class ArmorTypes {
    /** @var int[] */
    public const
      HELMET = [Item::LEATHER_HELMET, Item::CHAIN_HELMET, Item::IRON_HELMET, Item::GOLD_HELMET, Item::DIAMOND_HELMET], CHESTPLATE = [Item::LEATHER_CHESTPLATE, Item::CHAIN_CHESTPLATE, Item::IRON_CHESTPLATE, Item::GOLD_CHESTPLATE, Item::DIAMOND_CHESTPLATE, Item::ELYTRA], LEGGINGS = [Item::LEATHER_LEGGINGS, Item::CHAIN_LEGGINGS, Item::IRON_LEGGINGS, Item::GOLD_LEGGINGS, Item::DIAMOND_LEGGINGS], BOOTS = [Item::LEATHER_BOOTS, Item::CHAIN_BOOTS, Item::IRON_BOOTS, Item::GOLD_BOOTS, Item::DIAMOND_BOOTS];
    
    /** @var string */
    public const
      TYPE_HELMET = "HELMET", TYPE_CHESTPLATE = "CHESTPLATE", TYPE_LEGGINGS = "LEGGINGS", TYPE_BOOTS = "BOOTS", TYPE_NULL = "NIL";
    
    public static function getType(Item $armor): string{
        if(in_array($armor->getId(), $type = self::HELMET)){
            return self::TYPE_HELMET;
        }
        if(in_array($armor->getId(), self::CHESTPLATE)){
            return self::TYPE_CHESTPLATE;
        }
        if(in_array($armor->getId(), self::LEGGINGS)){
            return self::TYPE_LEGGINGS;
        }
        if(in_array($armor->getId(), self::BOOTS)){
            return self::TYPE_BOOTS;
        }
        return self::TYPE_NULL;
    }
}