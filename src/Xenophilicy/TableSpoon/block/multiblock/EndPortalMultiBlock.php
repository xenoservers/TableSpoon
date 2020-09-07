<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace Xenophilicy\TableSpoon\block\multiblock;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class EndPortalMultiblock
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
class EndPortalMultiBlock extends PortalMultiBlock {
    
    public function getTargetWorldInstance(): Level{
        return TableSpoon::$endLevel;
    }
    
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool{
        return false;
    }
    
    public function update(Block $wrapping): bool{
        return false;
    }
}