<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\event\player;

use pocketmine\event\Cancellable;
use pocketmine\level\Position;
use pocketmine\Player;
use Xenophilicy\TableSpoon\block\multiblock\PortalMultiBlock;
use Xenophilicy\TableSpoon\event\DimensionPortalsEvent;

/**
 * Class PlayerPortalTeleportEvent
 * @package Xenophilicy\TableSpoon\event\player
 */
class PlayerPortalTeleportEvent extends DimensionPortalsEvent implements Cancellable {
    /** @var Player */
    private $player;
    
    /** @var PortalMultiBlock */
    private $block;
    
    /** @var Position */
    private $target;
    
    /**
     * PlayerPortalTeleportEvent constructor.
     * @param Player $player
     * @param PortalMultiBlock $block
     * @param Position $target
     */
    public function __construct(Player $player, PortalMultiBlock $block, Position $target){
        $this->player = $player;
        $this->block = $block;
        $this->target = $target;
    }
    
    public function getPlayer(): Player{
        return $this->player;
    }
    
    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }
    
    public function getTarget(): Position{
        return $this->target->asPosition();
    }
    
    public function setTarget(Position $target): void{
        $this->target = $target->asPosition();
    }
}