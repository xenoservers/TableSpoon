<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\item\GlassBottle as PMGlassBottle;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class GlassBottle
 * @package Xenophilicy\TableSpoon\item
 */
class GlassBottle extends PMGlassBottle {
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        if(in_array($blockClicked->getId(), [Block::STILL_WATER, Block::FLOWING_WATER]) || in_array($blockReplace->getId(), [Block::STILL_WATER, Block::FLOWING_WATER])){
            if($player->isSurvival()){
                $this->count--;
            }
            $player->getInventory()->addItem(Item::get(Item::POTION, Potion::WATER, 1));
        }
        return true;
    }
}