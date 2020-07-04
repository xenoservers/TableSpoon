<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace Xenophilicy\TableSpoon\block\multiblock;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\Player;
use Xenophilicy\TableSpoon\player\PlayerSessionManager;

/**
 * Class PortalMultiBlock
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
abstract class PortalMultiBlock implements MultiBlock{

    /** @var int */
    private $teleportDuration;

    /**
     * PortalMultiBlock constructor.
     */
    public function __construct(){
        $this->teleportDuration = 1;
    }

    final public function getTeleportationDuration(): int{
        return $this->teleportDuration;
    }

    abstract public function getTargetWorldInstance(): Level;

    public function onPlayerMoveInside(Player $player, Block $block): void{
        PlayerSessionManager::get($player)->onEnterPortal($this);
    }

    public function onPlayerMoveOutside(Player $player, Block $block): void{
        PlayerSessionManager::get($player)->onLeavePortal();
    }
}