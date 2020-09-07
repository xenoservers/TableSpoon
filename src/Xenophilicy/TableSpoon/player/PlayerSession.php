<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\player;

use pocketmine\entity\Vehicle;
use pocketmine\level\Location;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use Xenophilicy\TableSpoon\block\multiblock\PortalMultiBlock;
use Xenophilicy\TableSpoon\entity\projectile\FishingHook;
use Xenophilicy\TableSpoon\event\player\PlayerEnterPortalEvent;
use Xenophilicy\TableSpoon\event\player\PlayerPortalTeleportEvent;
use Xenophilicy\TableSpoon\item\Elytra;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Session
 * @package Xenophilicy\TableSpoon
 */
class PlayerSession {
    /** @var int */
    public $lastEnderPearlUse = 0, $lastChorusFruitEat = 0, $lastHeldSlot = 0;
    /** @var bool */
    public $usingElytra = false, $allowCheats = false, $fishing = false;
    /** @var null | FishingHook */
    public $fishingHook = null;
    /** @var array */
    public $clientData = [];
    /** @var Vehicle */
    public $vehicle = null;
    /** @var Player */
    private $player;
    private $inPortal;
    private $changingDimension = false;
    
    /**
     * Session constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
    }
    
    public function __destruct(){
        $this->unsetFishing();
    }
    
    public function unsetFishing(){
        $this->fishing = false;
        if(!$this->fishingHook instanceof FishingHook) return;
        $this->fishingHook->broadcastEntityEvent(ActorEventPacket::FISH_HOOK_TEASE, null, $this->fishingHook->getViewers());
        if(!$this->fishingHook->isFlaggedForDespawn()) $this->fishingHook->flagForDespawn();
        $this->fishingHook = null;
    }
    
    public function getPlayer(): Player{
        return $this->player;
    }
    
    public function getServer(): Server{
        return $this->player->getServer();
    }
    
    /**
     * @param int $damage
     */
    public function damageElytra(int $damage = 1){
        if(!$this->player->isAlive() || !$this->player->isSurvival()) return;
        $inv = $this->player->getArmorInventory();
        $elytra = $inv->getChestplate();
        if(!$elytra instanceof Elytra) return;
        $elytra->applyDamage($damage);
    }
    
    public function isUsingElytra(): bool{
        if(!TableSpoon::$settings["player"]["elytra"]["enabled"]) return false;
        return ($this->player->getArmorInventory()->getChestplate() instanceof Elytra);
    }
    
    public function onEnterPortal(PortalMultiBlock $block): void{
        $ev = new PlayerEnterPortalEvent($this->player, $block, $block->getTeleportationDuration($this->player));
        $ev->call();
        if(!$ev->isCancelled()){
            $this->inPortal = new PlayerPortalInfo($block, $ev->getTeleportDuration());
            PlayerSessionManager::scheduleTicking($this->player);
        }
    }
    
    public function startDimensionChange(): void{
        $this->changingDimension = true;
    }
    
    public function endDimensionChange(): void{
        $this->changingDimension = false;
    }
    
    public function isChangingDimension(): bool{
        return $this->changingDimension;
    }
    
    public function tick(): void{
        if($this->inPortal->tick()){
            $this->teleport();
            $this->onLeavePortal();
        }
    }
    
    private function teleport(): void{
        $to = $this->inPortal->getBlock()->getTargetWorldInstance();
        $target = Location::fromObject(($this->player->getLevel() === $to ? TableSpoon::$overworldLevel : $to)->getSpawnLocation());
        ($ev = new PlayerPortalTeleportEvent($this->player, $this->inPortal->getBlock(), $target))->call();
        if(!$ev->isCancelled()){
            $pos = $ev->getTarget();
            if($target->getLevel() === TableSpoon::$netherLevel){
                $pos = Utils::genNetherSpawn($this->player->asPosition(), $target->getLevel());
            }
            $this->player->teleport($pos);
        }
    }
    
    public function onLeavePortal(): void{
        PlayerSessionManager::stopTicking($this->player);
        $this->inPortal = null;
    }
}
