<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\Pumpkin as PMPumpkin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class Pumpkin
 * @package Xenophilicy\TableSpoon\block
 */
class Pumpkin extends PMPumpkin {
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}