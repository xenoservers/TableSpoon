<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;
use Xenophilicy\TableSpoon\block\EndPortal;
use Xenophilicy\TableSpoon\block\Portal;
use Xenophilicy\TableSpoon\item\Minecart;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class EntityUtils
 * @package Xenophilicy\TableSpoon\utils
 */
class EntityUtils extends Utils {
    /** @var Entity[] */
    public static $ridingEntity = [];
    /** @var Entity[] */
    public static $riddenByEntity = [];
    
    public static function leashEntityToPlayer(Player $player, Entity $entity): bool{ // TODO: fix this
        $entityDPM = $entity->getDataPropertyManager();
        if($entityDPM->getByte(Entity::DATA_FLAG_LEASHED) != 1){
            $entityDPM->setByte(Entity::DATA_FLAG_LEASHED, 1, true);
            $entityDPM->setLong(Entity::DATA_LEAD_HOLDER_EID, $player->getId(), true);
            return true;
        }else{
            $entityDPM->removeProperty(Entity::DATA_FLAG_LEASHED);
            //$entityDPM->setByte(Entity::DATA_FLAG_LEASHED, 0, true);
            $entityDPM->setLong(Entity::DATA_LEAD_HOLDER_EID, -1, true);
            return false;
        }
    }
    
    public static function isInsideOfPortal(Entity $entity): bool{
        if($entity->level === null){
            return false;
        }
        $block = $entity->getLevel()->getBlock($entity->floor());
        if($block instanceof Portal){
            return true;
        }
        return false;
    }
    
    public static function isInsideOfEndPortal(Entity $entity): bool{
        if($entity->level === null){
            return false;
        }
        $block = $entity->getLevel()->getBlock($entity);
        if($block instanceof EndPortal){
            return true;
        }
        return false;
    }
    
    // Creds: Altay
    public static function mountEntity(Entity $vehicle, Entity $entity, int $type = EntityLink::TYPE_RIDER, bool $send = true): void{
        if(!isset(self::$ridingEntity[$entity->getId()]) and $entity !== $vehicle){
            self::$ridingEntity[$entity->getId()] = $vehicle;
            self::$riddenByEntity[$vehicle->getId()] = $entity;
            if($send){
                $dpm = $vehicle->getDataPropertyManager();
                $dpm->setVector3(Entity::DATA_RIDER_SEAT_POSITION, new Vector3(0, self::getMountedYOffset($vehicle), 0));
                if(!($vehicle instanceof Minecart)){
                    $dpm->setByte(Entity::DATA_RIDER_ROTATION_LOCKED, 1);
                    $dpm->setFloat(Entity::DATA_RIDER_MAX_ROTATION, 90);
                    $dpm->setFloat(Entity::DATA_RIDER_MIN_ROTATION, -90);
                }
                $entity->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_RIDING, true);
                $entity->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_WASD_CONTROLLED, true);
                $pk = new SetActorLinkPacket();
                $pk->link = new EntityLink($entity->getId(), $vehicle->getId(), $type, true, true);
                Server::getInstance()->broadcastPacket($entity->getViewers(), $pk);
                if($entity instanceof Player){
                    $entity->dataPacket($pk);
                }
            }
        }
    }
    
    private static function getMountedYOffset(Entity $entity): float{
        switch($entity->getId()){
            case Entity::BOAT:
                return 1.02001;
        }
        return 0;
    }
    
    public static function dismountEntity(Entity $vehicle, Entity $entity, bool $send = true): void{
        if(isset(self::$ridingEntity[$entity->getId()])){
            unset(self::$ridingEntity[$entity->getId()]);
            unset(self::$riddenByEntity[$vehicle->getId()]);
            if($send){
                $entity->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_RIDING, false);
                $entity->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_WASD_CONTROLLED, false);
                $dpm = $vehicle->getDataPropertyManager();
                $dpm->removeProperty(Entity::DATA_RIDER_SEAT_POSITION);
                if(!($vehicle instanceof Minecart)){
                    $dpm->removeProperty(Entity::DATA_RIDER_ROTATION_LOCKED);
                    $dpm->removeProperty(Entity::DATA_RIDER_MAX_ROTATION);
                    $dpm->removeProperty(Entity::DATA_RIDER_MIN_ROTATION);
                }
                $pk = new SetActorLinkPacket();
                $pk->link = new EntityLink($entity->getId(), $vehicle->getId(), EntityLink::TYPE_REMOVE, true, true);
                Server::getInstance()->broadcastPacket($entity->getViewers(), $pk);
                if($entity instanceof Player){
                    $entity->sendDataPacket($pk);
                }
            }
        }
    }
    
    /**
     * Returns if the structure is valid & the axis
     * @param Block $head
     * @return array
     */
    public static function checkSnowGolemStructure(Block $head): array{
        $level = $head->getLevel();
        $block1 = ($level->getBlock($head->subtract(0, 1, 0))->getId() == Block::SNOW_BLOCK);
        $block2 = ($level->getBlock($head->subtract(0, 2, 0))->getId() == Block::SNOW_BLOCK);
        return [($block1 && $block2), "Y"];
    }
    
    /**
     * Returns if the structure is valid & the axis
     * @param Block $head
     * @return array
     */
    public static function checkIronGolemStructure(Block $head): array{
        $level = $head->getLevel();
        $block1 = ($level->getBlock($head->subtract(0, 1, 0))->getId() == Block::IRON_BLOCK);
        $block2 = ($level->getBlock($head->subtract(0, 2, 0))->getId() == Block::IRON_BLOCK);
        // ARMS ON X AXIS
        $block3 = $level->getBlock($head->subtract(1, 1, 0));
        $block4 = $level->getBlock($head->add(1, -1, 0));
        // ARMS ON Z AXIS
        $block5 = $level->getBlock($head->subtract(0, 1, 1));
        $block6 = $level->getBlock($head->add(0, -1, 1));
        if($block1 && $block2){
            if($block3->getId() == Block::IRON_BLOCK && $block4->getId() == Block::IRON_BLOCK){
                return [true, "X"];
            }
            if($block5->getId() == Block::IRON_BLOCK && $block6->getId() == Block::IRON_BLOCK){
                return [true, "Z"];
            }
        }
        return [false, "NULL"];
    }
}