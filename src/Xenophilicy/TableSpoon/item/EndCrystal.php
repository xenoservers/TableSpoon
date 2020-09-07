<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class EndCrystal
 * @package Xenophilicy\TableSpoon\item
 */
class EndCrystal extends Item {
    /**
     * EndCrystal constructor.
     * @param int $meta
     * @param int $count
     */
    public function __construct($meta = 0, $count = 1){
        parent::__construct(Item::END_CRYSTAL, $meta, "Ender Crystal");
    }
    
    public function getMaxStackSize(): int{
        return 64;
    }
    
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        if(in_array($blockClicked->getId(), [Block::OBSIDIAN, Block::BEDROCK])){
            $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5));
            $crystal = Entity::createEntity("EnderCrystal", $player->getLevel(), $nbt);
            if($crystal instanceof \Xenophilicy\TableSpoon\entity\object\EndCrystal){
                $crystal->spawnToAll();
                if($player->isSurvival()){
                    --$this->count;
                }
            }
        }
        return true;
    }
}