<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Xp
 * @package Xenophilicy\TableSpoon\utils
 */
class Xp extends Utils {
    public static function getXpDropsForEntity(Entity $e): int{
        switch($e::NETWORK_ID){
            // animals //
            case Entity::CHICKEN:
            case Entity::COW:
            case Entity::HORSE:
            case Entity::DONKEY:
            case Entity::MULE:
            case Entity::SKELETON_HORSE:
            case Entity::ZOMBIE_HORSE:
            case Entity::MOOSHROOM:
            case Entity::LLAMA:
            case Entity::OCELOT:
            case Entity::PARROT:
            case Entity::PIG:
            case Entity::POLAR_BEAR:
            case Entity::SHEEP:
            case Entity::SQUID:
            case Entity::RABBIT:
            case Entity::WOLF:
                return mt_rand(1, 3);
            case Entity::BAT:
            case Entity::IRON_GOLEM:
            case Entity::SNOW_GOLEM:
            case Human::NETWORK_ID: // Handled by PMMP ;)
            case Entity::VILLAGER:
            case Entity::LIGHTNING_BOLT:
                return 0;
            case Entity::CAVE_SPIDER:
            case Entity::CREEPER:
            case Entity::ENDERMAN:
            case Entity::GHAST:
            case Entity::HUSK:
            case Entity::SHULKER:
            case Entity::SILVERFISH:
            case Entity::SKELETON:
            case Entity::SPIDER:
            case Entity::STRAY:
            case Entity::VINDICATOR:
            case Entity::WITCH:
            case Entity::WITHER_SKELETON:
            case Entity::ZOMBIE:
            case Entity::ZOMBIE_PIGMAN:
                return 5;
            case Entity::ENDERMITE:
            case Entity::VEX:
                return 3;
            case Entity::SLIME:
            case Entity::MAGMA_CUBE:
                return mt_rand(1, 4);
            case Entity::BLAZE:
            case Entity::GUARDIAN:
            case Entity::ELDER_GUARDIAN:
            case Entity::EVOCATION_ILLAGER:
                return 10;
            case Entity::ENDER_DRAGON:
                return (boolval(rand(0, 1)) ? 12000 : 500);
            case Entity::WITHER:
                return 50;
        }
        return 0;
    }
}