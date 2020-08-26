<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace Xenophilicy\TableSpoon\block\multiblock;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Interface MultiBlock
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
interface MultiBlock {
    
    /**
     * @param Block $wrapping
     * @param Player $player
     * @param Item $item
     * @param int $face
     * @return bool
     */
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool;
    
    /**
     * @param Block $wrapping
     * @return bool
     */
    public function update(Block $wrapping): bool;
    
    /**
     * @param Player $player
     * @param Block $block
     */
    public function onPlayerMoveInside(Player $player, Block $block): void;
    
    /**
     * @param Player $player
     * @param Block $block
     */
    public function onPlayerMoveOutside(Player $player, Block $block): void;
}