<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\SpawnEgg as PMSpawnEgg;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Xenophilicy\TableSpoon\block\MonsterSpawner;

/**
 * Class SpawnEgg
 * @package Xenophilicy\TableSpoon\item
 */
class SpawnEgg extends PMSpawnEgg {
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        $level = $player->getLevel();
        if(!($blockClicked instanceof MonsterSpawner)){
            $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), null, lcg_value() * 360, 0);
            
            if($this->hasCustomName()){
                $nbt->setString("CustomName", $this->getCustomName());
            }
            
            $entity = Entity::createEntity($this->meta, $level, $nbt);
            
            if($entity instanceof Entity){
                if($player->isSurvival()){
                    --$this->count;
                }
                $entity->spawnToAll();
                return true;
            }
            return false;
        }
        return false;
    }
}