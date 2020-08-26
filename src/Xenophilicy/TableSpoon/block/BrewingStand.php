<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\BrewingStand as PMBrewingStand;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\tile\BrewingStand as BrewingStandTile;

/**
 * Class BrewingStand
 * @package Xenophilicy\TableSpoon\block
 */
class BrewingStand extends PMBrewingStand {
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        $parent = parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        if(!$blockReplace->getSide(Vector3::SIDE_DOWN)->isTransparent()){
            $nbt = new CompoundTag("", [new StringTag(Tile::TAG_ID, Tile::BREWING_STAND), new IntTag(Tile::TAG_X, (int)$this->x), new IntTag(Tile::TAG_Y, (int)$this->y), new IntTag(Tile::TAG_Z, (int)$this->z)]);
            $nbt->setInt(BrewingStandTile::TAG_BREW_TIME, BrewingStandTile::MAX_BREW_TIME);
            if($item->hasCustomName()){
                $nbt->setString("CustomName", $item->getCustomName());
            }
            new BrewingStandTile($player->getLevel(), $nbt);
        }
        return $parent;
    }
    
    public function getLightLevel(): int{
        return 1;
    }
    
    public function getBlastResistance(): float{
        return 2.5;
    }
    
    public function onActivate(Item $item, Player $player = null): bool{
        if(!TableSpoon::$settings["blocks"]["brewing-stands"] || (TableSpoon::$settings["player"]["limited-creative"] && $player->isCreative())){
            return true;
        }
        $parent = parent::onActivate($item, $player);
        $tile = $player->getLevel()->getTile($this);
        if($tile instanceof BrewingStandTile){
            $player->addWindow($tile->getInventory());
        }else{
            $nbt = new CompoundTag("", [new StringTag(Tile::TAG_ID, Tile::BREWING_STAND), new IntTag(Tile::TAG_X, (int)$this->x), new IntTag(Tile::TAG_Y, (int)$this->y), new IntTag(Tile::TAG_Z, (int)$this->z)]);
            $nbt->setInt(BrewingStandTile::TAG_BREW_TIME, BrewingStandTile::MAX_BREW_TIME);
            if($item->hasCustomName()){
                $nbt->setString("CustomName", $item->getCustomName());
            }
            $tile = new BrewingStandTile($player->getLevel(), $nbt);
            $player->addWindow($tile->getInventory());
        }
        return $parent;
    }
}