<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\entity\Vehicle;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use pocketmine\Server as PMServer;
use Xenophilicy\TableSpoon\entity\projectile\FishingHook;
use Xenophilicy\TableSpoon\item\Elytra;

/**
 * Class Session
 * @package Xenophilicy\TableSpoon
 */
class Session{
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

        if($this->fishingHook instanceof FishingHook){
            $this->fishingHook->broadcastEntityEvent(ActorEventPacket::FISH_HOOK_TEASE, null, $this->fishingHook->getViewers());

            if(!$this->fishingHook->isFlaggedForDespawn()){
                $this->fishingHook->flagForDespawn();
            }

            $this->fishingHook = null;
        }
    }

    public function getPlayer(): Player{
        return $this->player;
    }

    public function getServer(): PMServer{
        return $this->player->getServer();
    }

    /**
     * @param int $damage
     */
    public function damageElytra(int $damage = 1){
        if(!$this->player->isAlive() || !$this->player->isSurvival()){
            return;
        }
        $inv = $this->player->getArmorInventory();
        $elytra = $inv->getChestplate();
        if($elytra instanceof Elytra){
            $elytra->applyDamage($damage);
        }
    }

    public function isUsingElytra(): bool{
        if(!TableSpoon::$settings["player"]["elytra"]["enabled"]){
            return false;
        }
        return ($this->player->getArmorInventory()->getChestplate() instanceof Elytra);
    }
}
