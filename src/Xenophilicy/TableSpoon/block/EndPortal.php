<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, Solid};
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\task\DelayedCrossDimensionTeleportTask;

/**
 * Class EndPortal
 * @package Xenophilicy\TableSpoon\block
 */
class EndPortal extends Solid {
    
    /** @var int $id */
    protected $id = Block::END_PORTAL;
    
    /**
     * EndPortal constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        $this->meta = $meta;
    }
    
    /**
     * @return int
     */
    public function getLightLevel(): int{
        return 1;
    }
    
    /**
     * @return string
     */
    public function getName(): string{
        return "End Portal";
    }
    
    /**
     * @return float
     */
    public function getHardness(): float{
        return -1;
    }
    
    /**
     * @return float
     */
    public function getBlastResistance(): float{
        return 18000000;
    }
    
    /**
     * @param Item $item
     * @return bool
     */
    public function isBreakable(Item $item): bool{
        return false;
    }
    
    /**
     * @return bool
     */
    public function canPassThrough(): bool{
        return true;
    }
    
    /**
     * @return bool
     */
    public function hasEntityCollision(): bool{
        return true;
    }
    
    
    /**
     * @param Entity $entity
     *
     */
    public function onEntityCollide(Entity $entity): void{
        if(TableSpoon::$settings["dimensions"]["nether"]["enabled"] || TableSpoon::$settings["dimensions"]["end"]["enabled"]){
            if($entity->getLevel()->getSafeSpawn()->distance($entity->asVector3()) <= 0.1){
                return;
            }
            if(!isset(TableSpoon::$onPortal[$entity->getId()])){
                TableSpoon::$onPortal[$entity->getId()] = true;
                if($entity instanceof Player){
                    if($entity->getLevel() instanceof Level){
                        $plug = TableSpoon::getInstance();
                        if($entity->getLevel()->getName() != TableSpoon::$settings["dimensions"]["end"]["enabled"]){
                            $plug->getScheduler()->scheduleDelayedTask(new DelayedCrossDimensionTeleportTask($entity, DimensionIds::THE_END, TableSpoon::$endLevel->getSafeSpawn()), 1);
                        }else{
                            $plug->getScheduler()->scheduleDelayedTask(new DelayedCrossDimensionTeleportTask($entity, DimensionIds::OVERWORLD, TableSpoon::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn()), 1);
                        }
                    }
                }
                // TODO: Add mob teleportation
            }
        }
        return;
    }
}