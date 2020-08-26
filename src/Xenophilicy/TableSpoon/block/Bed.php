<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Bed as PMBed;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Bed
 * @package Xenophilicy\TableSpoon\block
 */
class Bed extends PMBed {
    
    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool{
        $dimension = Utils::getDimension($this->getLevel());
        if($dimension == DimensionIds::NETHER || $dimension == DimensionIds::THE_END){
            $explosion = new Explosion($this, 6);
            $explosion->explodeA();
            $explosion->explodeB();
            return true;
        }
        return parent::onActivate($item, $player);
    }
}