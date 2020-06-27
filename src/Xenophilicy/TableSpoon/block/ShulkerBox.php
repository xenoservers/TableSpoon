<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, BlockToolType, Transparent};
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Container;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\tile\{ShulkerBox as TileShulkerBox, Tile};

/**
 * Class ShulkerBox
 * @package Xenophilicy\TableSpoon\block
 */
class ShulkerBox extends Transparent {
    
    /**
     * ShulkerBox constructor.
     * @param int $id
     * @param int $meta
     */
    public function __construct(int $id = self::SHULKER_BOX, int $meta = 0){
        $this->id = $id;
        $this->meta = $meta;
    }
    
    public function getResistance(): float{
        return 30;
    }
    
    public function getHardness(): float{
        return 2;
    }
    
    public function getToolType(): int{
        return BlockToolType::TYPE_PICKAXE;
    }
    
    public function getName(): string{
        return "Shulker Box";
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        // TODO: Rotation
        $this->getLevel()->setBlock($blockReplace, $this, true, true);
        $nbt = TileShulkerBox::createNBT($this, $face, $item, $player); // why tf isnt it loading the items... reee
        $items = $item->getNamedTag()->getTag(Container::TAG_ITEMS);
        if($items !== null){
            $nbt->setTag($items);
        }
        Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), $nbt);
        
        ($inv = $player->getInventory())->clear($inv->getHeldItemIndex()); // TODO: We need PMMP to be able to set max stack size in blocks... ree
        return true;
    }
    
    public function onBreak(Item $item, Player $player = null): bool{
        /** @var TileShulkerBox $t */
        $t = $this->getLevel()->getTile($this);
        if($t instanceof TileShulkerBox){
            $item = ItemFactory::get($this->id, $this->id != self::UNDYED_SHULKER_BOX ? $this->meta : 0, 1);
            $itemNBT = clone $item->getNamedTag();
            $itemNBT->setTag($t->getCleanedNBT()->getTag(Container::TAG_ITEMS));
            $item->setNamedTag($itemNBT);
            $this->getLevel()->dropItem($this->add(0.5, 0.5, 0.5), $item);
            
            $t->getInventory()->clearAll(); // dont drop the items
        }
        $this->getLevel()->setBlock($this, Block::get(Block::AIR), true, true);
        return true;
    }
    
    public function onActivate(Item $item, Player $player = null): bool{
        if(TableSpoon::$settings["blocks"]["shulker-box"]){
            if($player instanceof Player){
                $t = $this->getLevel()->getTile($this);
                if(!($t instanceof TileShulkerBox)){
                    $t = Tile::createTile(Tile::SHULKER_BOX, $this->getLevel(), TileShulkerBox::createNBT($this));
                }
                if(!$this->getSide(Vector3::SIDE_UP)->isTransparent() || !$t->canOpenWith($item->getCustomName()) || ($player->isCreative() && TableSpoon::$settings["player"]["limited-creative"])){
                    return true;
                }
                $player->addWindow($t->getInventory());
            }
        }
        return true;
    }
    
    public function getDrops(Item $item): array{
        return [];
    }
}