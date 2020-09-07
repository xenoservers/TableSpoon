<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item\utils;

use pocketmine\item\Item;

/**
 * Class ArmorDurability
 * @package Xenophilicy\TableSpoon\item\utils
 */
class ArmorDurability {
    // Just to make it more organized...
    // VALUES ARE BASED FROM: https://minecraft.gamepedia.com/Helmet, https://minecraft.gamepedia.com/Chestplate, https://minecraft.gamepedia.com/Leggings, https://minecraft.gamepedia.com/Boots
    /** @var int[] */
    public const
      LEATHER_DURABILITY = [Item::LEATHER_HELMET => 56, Item::LEATHER_CHESTPLATE => 81, Item::LEATHER_LEGGINGS => 76, Item::LEATHER_BOOTS => 66], CHAIN_DURABILITY = [Item::CHAIN_HELMET => 166, Item::CHAIN_CHESTPLATE => 241, Item::CHAIN_LEGGINGS => 226, Item::CHAIN_BOOTS => 196], IRON_DURABILITY = [Item::IRON_HELMET => 166, Item::IRON_CHESTPLATE => 241, Item::IRON_LEGGINGS => 226, Item::IRON_BOOTS => 196], GOLD_DURABILITY = [Item::GOLD_HELMET => 78, Item::GOLD_CHESTPLATE => 113, Item::GOLD_LEGGINGS => 102, Item::GOLD_BOOTS => 92], DIAMOND_DURABILITY = [Item::DIAMOND_HELMET => 364, Item::DIAMOND_CHESTPLATE => 529, Item::DIAMOND_LEGGINGS => 496, Item::DIAMOND_BOOTS => 430];
    
    /** @var int */
    public const DURABILITY = [Item::LEATHER_HELMET => 56, Item::LEATHER_CHESTPLATE => 81, Item::LEATHER_LEGGINGS => 76, Item::LEATHER_BOOTS => 66,
      
      Item::CHAIN_HELMET => 166, Item::CHAIN_CHESTPLATE => 241, Item::CHAIN_LEGGINGS => 226, Item::CHAIN_BOOTS => 196,
      
      Item::IRON_HELMET => 166, Item::IRON_CHESTPLATE => 241, Item::IRON_LEGGINGS => 226, Item::IRON_BOOTS => 196,
      
      Item::GOLD_HELMET => 78, Item::GOLD_CHESTPLATE => 113, Item::GOLD_LEGGINGS => 102, Item::GOLD_BOOTS => 92,
      
      Item::DIAMOND_HELMET => 364, Item::DIAMOND_CHESTPLATE => 529, Item::DIAMOND_LEGGINGS => 496, Item::DIAMOND_BOOTS => 430,
      
      Item::ELYTRA => 431];
    
    /** @var int */
    public const OTHERS = [Item::ELYTRA => 431];
    
    /** @var int[] */
    public const NON_ARMOR_WEARABLES = [Item::MOB_HEAD, Item::PUMPKIN, Item::AIR, // whenever the player isn't wearing something for that inventory slot...
    ];
    
    public static function getDurability(int $id): int{
        if(in_array($id, self::NON_ARMOR_WEARABLES)){
            return -1;
        }
        if(isset(self::DURABILITY[$id])){
            return self::DURABILITY[$id];
        }
        return -1;
    }
}