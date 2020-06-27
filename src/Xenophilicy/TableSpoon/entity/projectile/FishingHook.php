<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\projectile;


use pocketmine\block\StillWater;
use pocketmine\block\Water;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\math\RayTraceResult;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use pocketmine\Server as PMServer;

/**
 * Class FishingHook
 * @package Xenophilicy\TableSpoon\entity\projectile
 */
class FishingHook extends Projectile {
    
    public const NETWORK_ID = self::FISHING_HOOK;
    
    public $width = 0.25;
    public $length = 0.25;
    public $height = 0.25;
    public $coughtTimer = 0;
    public $attractTimer = 0;
    protected $gravity = 0.1;
    protected $drag = 0.05;
    protected $touchedWater = false;
    
    public function onUpdate(int $currentTick): bool{
        if($this->isFlaggedForDespawn() || !$this->isAlive()){
            return false;
        }
        $this->timings->startTiming();
        $hasUpdate = parent::onUpdate($currentTick);
        if($this->isCollidedVertically){
            $this->motion->x = 0;
            $this->motion->y += 0.01;
            $this->motion->z = 0;
            $hasUpdate = true;
        }elseif($this->isCollided && $this->keepMovement === true){
            $this->motion->x = 0;
            $this->motion->y = 0;
            $this->motion->z = 0;
            $this->keepMovement = false;
            $hasUpdate = true;
        }
        if($this->isCollided && !$this->touchedWater){
            foreach($this->getBlocksAround() as $block){
                if($block instanceof Water || $block instanceof StillWater){
                    $this->touchedWater = true;
                    $pk = new ActorEventPacket();
                    $pk->entityRuntimeId = $this->getId();
                    $pk->event = ActorEventPacket::FISH_HOOK_POSITION;
                    PMServer::getInstance()->broadcastPacket($this->getViewers(), $pk);
                    break;
                }
            }
        }
        if($this->attractTimer === 0 && mt_rand(0, 100) <= 30){
            $this->coughtTimer = mt_rand(5, 10) * 20;
            $this->attractTimer = mt_rand(30, 100) * 20;
            $this->attractFish();
            $oe = $this->getOwningEntity();
            if($oe instanceof Player){
                $oe->sendTip("A fish bites!");
            }
        }elseif($this->attractTimer > 0){
            $this->attractTimer--;
        }
        if($this->coughtTimer > 0){
            $this->coughtTimer--;
            $this->fishBites();
        }
        $this->timings->stopTiming();
        return $hasUpdate;
    }
    
    public function attractFish(){
        $oe = $this->getOwningEntity();
        if($oe instanceof Player){
            $pk = new ActorEventPacket();
            $pk->entityRuntimeId = $this->getId();
            $pk->event = ActorEventPacket::FISH_HOOK_BUBBLE;
            PMServer::getInstance()->broadcastPacket($this->getViewers(), $pk);
        }
    }
    
    public function fishBites(){
        $oe = $this->getOwningEntity();
        if($oe instanceof Player){
            $pk = new ActorEventPacket();
            $pk->entityRuntimeId = $this->getId();
            $pk->event = ActorEventPacket::FISH_HOOK_HOOK;
            PMServer::getInstance()->broadcastPacket($this->getViewers(), $pk);
        }
    }
    
    public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void{
        $event = new ProjectileHitEntityEvent($this, $hitResult, $entityHit);
        $event->call();
        $damage = $this->getResultDamage();
        if($this->getOwningEntity() === null){
            $ev = new EntityDamageByEntityEvent($this, $entityHit, EntityDamageEvent::CAUSE_PROJECTILE, $damage);
        }else{
            $ev = new EntityDamageByChildEntityEvent($this->getOwningEntity(), $this, $entityHit, EntityDamageEvent::CAUSE_PROJECTILE, $damage);
        }
        $entityHit->attack($ev);
        $entityHit->setMotion($this->getOwningEntity()->getDirectionVector()->multiply(-0.3)->add(0, 0.3, 0));
        $this->isCollided = true;
        $this->flagForDespawn();
    }
    
    public function getResultDamage(): int{
        return 1;
    }
}
