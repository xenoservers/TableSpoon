<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\Bow as PMBow;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use Xenophilicy\TableSpoon\entity\projectile\Arrow;

/**
 * Class Bow
 * @package Xenophilicy\TableSpoon\item
 */
class Bow extends PMBow {
    public function onReleaseUsing(Player $player): bool{
        if($player->isSurvival() and !$player->getInventory()->contains(Item::get(Item::ARROW, 0, 1))){
            $player->getInventory()->sendContents($player);
            return false;
        }
        $skipcheckItem = false;
        if(!$player->getInventory()->contains(Item::get(Item::ARROW, 0, 1))){
            $skipcheckItem = true;
        }
        if(!$skipcheckItem){
            $first = $player->getInventory()->getItem($player->getInventory()->first(Item::get(Item::ARROW, -1, 1), false));
        }else{
            $first = Item::get(Item::ARROW, 0, 1);
        }
        $nbt = Entity::createBaseNBT($player->add(0, $player->getEyeHeight(), 0), $player->getDirectionVector(), ($player->yaw > 180 ? 360 : 0) - $player->yaw, -$player->pitch);
        $nbt->setShort("Fire", $player->isOnFire() ? 45 * 60 : 0);
        if($first->getDamage() >= 1 && $first->getDamage() <= 36){
            $nbt->setShort("Potion", $first->getDamage() - 1);
        }
        $diff = $player->getItemUseDuration();
        $p = $diff / 20;
        $force = min((($p ** 2) + $p * 2) / 3, 1) * 2;
        $entity = new Arrow($player->getLevel(), $nbt, $player, $force == 2);
        //$entity = Entity::createEntity("Arrow", $player->getLevel(), $nbt, $player, $force == 2);
        if($entity instanceof Projectile){
            $ev = new EntityShootBowEvent($player, $this, $entity, $force);
            if($force < 0.1 or $diff < 5){
                $ev->setCancelled();
            }
            $ev->call();
            $entity = $ev->getProjectile();
            if($ev->isCancelled()){
                $entity->flagForDespawn();
                $player->getInventory()->sendContents($player);
            }else{
                $entity->setMotion($entity->getMotion()->multiply($ev->getForce()));
                $unbreaking = false;
                $infinity = false;
                if($this->hasEnchantments()){
                    if($this->hasEnchantment(Enchantment::FLAME)){
                        $enchantment = $this->getEnchantment(Enchantment::FLAME);
                        $lvl = $enchantment->getLevel() + 4;
                        $entity->setOnFire($lvl * 20);
                    }
                    if($this->hasEnchantment(Enchantment::UNBREAKING)){
                        $enchantment = $this->getEnchantment(Enchantment::UNBREAKING);
                        $lvl = $enchantment->getLevel() + 1;
                        if(mt_rand(1, 100) >= intval(100 / $lvl)){
                            $unbreaking = true;
                        }
                    }
                    if($this->hasEnchantment(Enchantment::INFINITY)){
                        $infinity = true;
                    }
                }
                if($player->isSurvival()){
                    if(!$infinity){
                        $first->setCount(1);
                        $player->getInventory()->removeItem($first);
                    }
                    if(!$unbreaking){
                        $this->applyDamage(1);
                    }
                }
                if($entity instanceof Projectile){
                    $projectileEv = new ProjectileLaunchEvent($entity);
                    $projectileEv->call();
                    if($projectileEv->isCancelled()){
                        $ev->getProjectile()->flagForDespawn();
                    }else{
                        $ev->getProjectile()->spawnToAll();
                        $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_BOW);
                    }
                }else{
                    $entity->spawnToAll();
                }
            }
        }else{
            $entity->spawnToAll();
        }
        return true;
    }
}
