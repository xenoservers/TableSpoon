<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item\enchantment;

use pocketmine\item\enchantment\Enchantment as EnchantmentPM;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class Enchantment
 * @package Xenophilicy\TableSpoon\item\enchantment
 */
class Enchantment extends EnchantmentPM {
    public static function init(): void{
        if(TableSpoon::$settings["enchantments"]["vanilla"]){
            self::registerEnchantment(new Enchantment(self::SMITE, "%enchantment.weapon.smite", self::RARITY_UNCOMMON, self::SLOT_SWORD, self::SLOT_NONE, 5));
            self::registerEnchantment(new Enchantment(self::BANE_OF_ARTHROPODS, "%enchantment.weapon.arthropods", self::RARITY_UNCOMMON, self::SLOT_SWORD, self::SLOT_NONE, 5));
            self::registerEnchantment(new Enchantment(self::LOOTING, "%enchantment.weapon.looting", self::RARITY_UNCOMMON, self::SLOT_SWORD, self::SLOT_NONE, 3));
            self::registerEnchantment(new Enchantment(self::FORTUNE, "%enchantment.mining.fortune", self::RARITY_UNCOMMON, self::SLOT_TOOL, self::SLOT_NONE, 3));
            self::registerEnchantment(new Enchantment(self::LUCK_OF_THE_SEA, "%enchantment.fishing.fortune", self::RARITY_UNCOMMON, self::SLOT_FISHING_ROD, self::SLOT_NONE, 3));
            self::registerEnchantment(new Enchantment(self::LURE, "%enchantment.fishing.lure", self::RARITY_UNCOMMON, self::SLOT_FISHING_ROD, self::SLOT_NONE, 3));
            self::registerEnchantment(new Enchantment(self::FROST_WALKER, "%enchantment.waterwalk", self::RARITY_UNCOMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2)); // TODO: verify name
            self::registerEnchantment(new Enchantment(self::MENDING, "%enchantment.mending", self::RARITY_UNCOMMON, self::SLOT_ARMOR, self::SLOT_NONE, 1)); // TODO: verify name
        }
    }
}
