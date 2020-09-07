<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\item\Bucket as PMBucket;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Bucket
 * @package Xenophilicy\TableSpoon\item
 */
class Bucket extends PMBucket {
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        if(Utils::getDimension($player->getLevel()) == DimensionIds::NETHER && $this->getOutputBlockID() == Block::WATER){
            return false;
        }
        return parent::onActivate($player, $blockReplace, $blockClicked, $face, $clickVector);
    }
    
    public function getOutputBlockID(): int{
        return $this->meta + 1;
    }
}