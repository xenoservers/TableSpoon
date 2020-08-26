<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\{network\mcpe\protocol\types\DimensionIds, Server};
use pocketmine\block\Lava as PMLava;
use pocketmine\entity\Entity;
use pocketmine\event\entity\{EntityCombustByBlockEvent, EntityDamageByBlockEvent, EntityDamageEvent};
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Lava
 * @package Xenophilicy\TableSpoon\block
 */
class Lava extends PMLava {
    
    /**
     * @param Entity $entity
     */
    public function onEntityCollide(Entity $entity): void{
        if((Server::getInstance()->getTick() % $this->tickRate()) == 0){
            $entity->fallDistance *= 0.5;
            $ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
            $entity->attack($ev);
        }
        $ev = new EntityCombustByBlockEvent($this, $entity, 15);
        $ev->call();
        if(!$ev->isCancelled()){
            $entity->setOnFire($ev->getDuration());
        }
        $entity->resetFallDistance();
    }
    
    public function getFlowDecayPerBlock(): int{
        return (Utils::getDimension($this->getLevel()) == DimensionIds::NETHER) ? 1 : 2;
    }
}
