<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, MonsterSpawner as SpawnerPM};
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Tile;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\tile\MobSpawner;

/**
 * Class MonsterSpawner
 * @package Xenophilicy\TableSpoon\block
 */
class MonsterSpawner extends SpawnerPM {
    
    /**
     * MonsterSpawner constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }
    
    /**
     * @return bool
     */
    public function canBeActivated(): bool{
        return true;
    }
    
    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool{
        if(TableSpoon::$settings["blocks"]["spawners"]["enabled"] && $item->getId() == Item::SPAWN_EGG){
            $tile = $this->getLevel()->getTile($this);
            if(!($tile instanceof MobSpawner)){
                $nbt = MobSpawner::createNBT($this);
                $tile = Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
                if($tile instanceof MobSpawner){
                    $tile->setEntityId($item->getDamage());
                    if(!$player->isCreative()){
                        $item->pop();
                    }
                    return true;
                }
            }
        }
        return false;
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        $eID = null;
        $nbt = MobSpawner::createNBT($this, $face, $item, $player);
        if($item->getNamedTag()->getTag(MobSpawner::TAG_ENTITY_ID) !== null){
            foreach([MobSpawner::TAG_ENTITY_ID, MobSpawner::TAG_DELAY, MobSpawner::TAG_MIN_SPAWN_DELAY, MobSpawner::TAG_MAX_SPAWN_DELAY, MobSpawner::TAG_SPAWN_COUNT, MobSpawner::TAG_SPAWN_RANGE,] as $tag_name){
                $tag = $item->getNamedTag()->getTag($tag_name);
                if($tag !== null){
                    $nbt->setTag($tag);
                }
            }
        }elseif(($meta = $item->getDamage()) != 0){
            $nbt->setInt(MobSpawner::TAG_ENTITY_ID, $meta);
        }else{
            return true;
        }
        Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
        return true;
    }
    
    public function getSilkTouchDrops(Item $item): array{
        $tile = $this->getLevel()->getTile($this);
        if($tile instanceof MobSpawner){
            return [ItemFactory::get(Item::MONSTER_SPAWNER, 0, 1, $tile->getCleanedNBT()),];
        }
        return parent::getSilkTouchDrops($item);
    }
    
    public function isAffectedBySilkTouch(): bool{
        return TableSpoon::$settings["blocks"]["spawners"]["silk-touch"];
    }
}
