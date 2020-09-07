<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\item\Minecart as PMMinecart;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class Minecart
 * @package Xenophilicy\TableSpoon\item
 */
class Minecart extends PMMinecart {
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        //$level = $player->getLevel();
        //$entity = Entity::createEntity(Entity::MINECART, $level, Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5)));
        
        //$entity->spawnToAll();
        if($player->isSurvival()){
            $this->count--;
        }
        return true;
    }
}