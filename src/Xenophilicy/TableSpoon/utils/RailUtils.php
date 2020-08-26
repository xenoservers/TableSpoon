<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;

use pocketmine\block\Block;

/**
 * INTERNAL helper for Railway
 * <p>
 * By lmlstarqaq http://snake1999.com/
 * Rewrite by larryTheCoder
 * @package Xenophilicy\TableSpoon\utils
 */
class RailUtils {
    
    /**
     * @param $block
     * @return bool
     */
    public static function isRailBlock($block): bool{
        if(is_null($block)) return false;
        $id = $block;
        if($block instanceof Block) $id = $block->getId();
        switch($id){
            case Block::RAIL:
            case Block::POWERED_RAIL:
            case Block::ACTIVATOR_RAIL:
            case Block::DETECTOR_RAIL:
                return true;
            default:
                return false;
        }
    }
    
}

