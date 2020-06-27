<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity;

use pocketmine\entity\Entity;
use Xenophilicy\TableSpoon\entity\mob\{Bat,
  Blaze,
  CaveSpider,
  Chicken,
  Cow,
  Creeper,
  Donkey,
  ElderGuardian,
  EnderDragon,
  Enderman,
  Endermite,
  Evoker,
  Ghast,
  Guardian,
  Horse,
  Husk,
  IronGolem,
  Llama,
  MagmaCube,
  Mooshroom,
  Mule,
  Ocelot,
  Parrot,
  Pig,
  PigZombie,
  PolarBear,
  Rabbit,
  Sheep,
  Shulker,
  Silverfish,
  Skeleton,
  Slime,
  SnowGolem,
  Spider,
  Stray,
  Vex,
  Vindicator,
  Witch,
  Wither,
  WitherSkeleton,
  Wolf,
  ZombieHorse,
  ZombieVillager};
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
        if(TableSpoon::$settings["entities"]["register"]){
            self::registerEntity(Bat::class, true, ['Bat', 'minecraft:bat']);
            self::registerEntity(Blaze::class, true, ['Blaze', 'minecraft:blaze']);
            self::registerEntity(CaveSpider::class, true, ['CaveSpider', 'minecraft:cavespider']);
            self::registerEntity(Chicken::class, true, ['Chicken', 'minecraft:chicken']);
            self::registerEntity(Cow::class, true, ['Cow', 'minecraft:cow']);
            self::registerEntity(Creeper::class, true, ['Creeper', 'minecraft:creeper']);
            self::registerEntity(Donkey::class, true, ['Donkey', 'minecraft:donkey']);
            self::registerEntity(ElderGuardian::class, true, ['ElderGuardian', 'minecraft:elderguardian']);
            self::registerEntity(EnderDragon::class, true, ['EnderDragon', 'minecraft:enderdragon']);
            self::registerEntity(Enderman::class, true, ['Enderman', 'minecraft:enderman']);
            self::registerEntity(Endermite::class, true, ['Endermite', 'minecraft:endermite']);
            self::registerEntity(Evoker::class, true, ['Evoker', 'minecraft:evoker']);
            self::registerEntity(Ghast::class, true, ['Ghast', 'minecraft:ghast']);
            self::registerEntity(Guardian::class, true, ['Guardian', 'minecraft:guardian']);
            self::registerEntity(Horse::class, true, ['Horse', 'minecraft:horse']);
            self::registerEntity(Husk::class, true, ['Husk', 'minecraft:husk']);
            self::registerEntity(IronGolem::class, true, ['IronGolem', 'minecraft:irongolem']);
            self::registerEntity(Llama::class, true, ['Llama', 'minecraft:llama']);
            self::registerEntity(MagmaCube::class, true, ['MagmaCube', 'minecraft:magmacube']);
            self::registerEntity(Mooshroom::class, true, ['Mooshroom', 'minecraft:mooshroom']);
            self::registerEntity(Mule::class, true, ['Mule', 'minecraft:mule']);
            self::registerEntity(Ocelot::class, true, ['Ocelot', 'minecraft:ocelot']);
            self::registerEntity(Parrot::class, true, ['Parrot', 'minecraft:parrot']);
            self::registerEntity(Pig::class, true, ['Pig', 'minecraft:pig']);
            self::registerEntity(PigZombie::class, true, ['PigZombie', 'minecraft:pigzombie']);
            self::registerEntity(PolarBear::class, true, ['PolarBear', 'minecraft:polarbear']);
            self::registerEntity(Rabbit::class, true, ['Rabbit', 'minecraft:rabbit']);
            self::registerEntity(Sheep::class, true, ['Sheep', 'minecraft:sheep']);
            self::registerEntity(Shulker::class, true, ['Shulker', 'minecraft:shulker']);
            self::registerEntity(Silverfish::class, true, ['Silverfish', 'minecraft:silverfish']);
            self::registerEntity(Skeleton::class, true, ['Skeleton', 'minecraft:skeleton']);
            self::registerEntity(Slime::class, true, ['Slime', 'minecraft:slime']);
            self::registerEntity(SnowGolem::class, true, ['SnowGolem', 'minecraft:snowgolem']);
            self::registerEntity(Spider::class, true, ['Spider', 'minecraft:spider']);
            self::registerEntity(Stray::class, true, ['Stray', 'minecraft:stray']);
            self::registerEntity(Vex::class, true, ['Vex', 'minecraft:vex']);
            self::registerEntity(Vindicator::class, true, ['Vindicator', 'minecraft:vindicator']);
            self::registerEntity(Witch::class, true, ['Witch', 'minecraft:witch']);
            self::registerEntity(Wither::class, true, ['Wither', 'minecraft:wither']);
            self::registerEntity(WitherSkeleton::class, true, ['WitherSkeleton', 'minecraft:witherskeleton']);
            self::registerEntity(Wolf::class, true, ['Wolf', 'minecraft:wolf']);
            self::registerEntity(ZombieHorse::class, true, ['ZombieHorse', 'minecraft:zombiehorse']);
            self::registerEntity(ZombieVillager::class, true, ['ZombieVillager', 'minecraft:zombievillager']);
        }
        // Projectiles ////
        self::registerEntity(LingeringPotion::class, true, ['LingeringPotion', 'minecraft:lingeringpotion']);
        self::registerEntity(FishingHook::class, true, ['FishingHook', 'minecraft:fishinghook']);
        self::registerEntity(Arrow::class, true, ['Arrow', 'minecraft:arrow']);
        self::registerEntity(FireworkRocket::class, true, ['Firework', 'minecraft:firework']);
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
