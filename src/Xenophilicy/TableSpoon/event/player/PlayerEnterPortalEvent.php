<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;
use Xenophilicy\TableSpoon\block\multiblock\PortalMultiBlock;
use Xenophilicy\TableSpoon\event\DimensionPortalsEvent;

/**
 * Class PlayerEnterPortalEvent
 * @package Xenophilicy\TableSpoon\event\player
 */
class PlayerEnterPortalEvent extends DimensionPortalsEvent implements Cancellable {
    
    /** @var Player */
    private $player;
    
    /** @var PortalMultiBlock */
    private $block;
    
    /** @var int */
    private $teleportDuration;
    
    /**
     * PlayerEnterPortalEvent constructor.
     * @param Player $player
     * @param PortalMultiBlock $block
     * @param int $teleportDuration
     */
    public function __construct(Player $player, PortalMultiBlock $block, int $teleportDuration){
        $this->player = $player;
        $this->block = $block;
        $this->teleportDuration = $teleportDuration;
    }
    
    public function getPlayer(): Player{
        return $this->player;
    }
    
    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }
    
    public function getTeleportDuration(): int{
        return $this->teleportDuration;
    }
    
    public function setTeleportDuration(int $teleportDuration): void{
        $this->teleportDuration = $teleportDuration;
    }
}