<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;


use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Xenophilicy\TableSpoon\entity\object\ArmorStand as ArmorStandEntity;

/**
 * Class ArmorStand
 * @package Xenophilicy\TableSpoon\item
 */
class ArmorStand extends Item {
    /**
     * ArmorStand constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(self::ARMOR_STAND, $meta, "Armor Stand");
    }
    
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        $entity = Entity::createEntity(Entity::ARMOR_STAND, $player->getLevel(), Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), null, $this->getDirection($player->getYaw())));
        
        if($entity instanceof ArmorStandEntity){
            if($player->isSurvival()){
                $this->pop();
            }
            $entity->spawnToAll();
        }
        return true;
    }
    
    /**
     * @param $yaw
     * @return float
     */
    public function getDirection($yaw): float{
        $rotation = $yaw % 360;
        if($rotation < 0){
            $rotation += 360;
        }
        if((0 <= $rotation && $rotation < 22.5) || (337.5 <= $rotation && $rotation < 360)){
            return 180;
        }elseif(22.5 <= $rotation && $rotation < 67.5){
            return 225;
        }elseif(67.5 <= $rotation && $rotation < 112.5){
            return 270;
        }elseif(112.5 <= $rotation && $rotation < 157.5){
            return 315;
        }elseif(157.5 <= $rotation && $rotation < 202.5){
            return 0;
        }elseif(202.5 <= $rotation && $rotation < 247.5){
            return 45;
        }elseif(247.5 <= $rotation && $rotation < 292.5){
            return 90;
        }elseif(292.5 <= $rotation && $rotation < 337.5){
            return 135;
        }else{
            return 0;
        }
    }
}