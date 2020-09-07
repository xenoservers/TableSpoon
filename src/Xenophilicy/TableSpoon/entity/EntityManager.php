<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity;

use pocketmine\entity\Entity;
use Xenophilicy\TableSpoon\entity\object\{AreaEffectCloud, ArmorStand, EndCrystal, Lightning};
use Xenophilicy\TableSpoon\entity\projectile\{Arrow, FireworkRocket, FishingHook, LingeringPotion, ThrownTrident};
use Xenophilicy\TableSpoon\entity\vehicle\Boat;
use Xenophilicy\TableSpoon\entity\vehicle\Minecart;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class EntityManager
 * @package Xenophilicy\TableSpoon\entity
 */
class EntityManager extends Entity {
    public static function init(): void{
        // Projectiles ////
        self::registerEntity(LingeringPotion::class, true, ['LingeringPotion', 'minecraft:lingeringpotion']);
        self::registerEntity(FishingHook::class, true, ['FishingHook', 'minecraft:fishinghook']);
        self::registerEntity(Arrow::class, true, ['Arrow', 'minecraft:arrow']);
        self::registerEntity(FireworkRocket::class, true, ['FireworkRocket', 'minecraft:firework']);
        self::registerEntity(ThrownTrident::class, true, ['Trident', 'minecraft:trident']);
        // Other Entities ////
        self::registerEntity(AreaEffectCloud::class, true, ['AreaEffectCloud', 'minecraft:areaeffectcloud']);
        self::registerEntity(Lightning::class, true, ['Lightning', 'minecraft:lightning']);
        self::registerEntity(EndCrystal::class, true, ['EnderCrystal', 'minecraft:ender_crystal']);
        self::registerEntity(Boat::class, true, ['Boat', 'minecraft:boat']);
        self::registerEntity(ArmorStand::class, true, ['ArmorStand', 'minecraft:armor_stand']);
        if(TableSpoon::$settings["entities"]["minecarts"]){
            self::registerEntity(Minecart::class, true, ['Minecart', 'minecraft:minecart']);
        }
    }
}
