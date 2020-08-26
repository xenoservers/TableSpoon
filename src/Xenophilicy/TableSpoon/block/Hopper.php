<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\{block\Block, block\BlockToolType, block\Transparent, item\Item, math\Vector3, nbt\tag\CompoundTag, nbt\tag\IntTag, nbt\tag\ListTag, nbt\tag\StringTag,
  Player};
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\tile\Hopper as HopperTile;
use Xenophilicy\TableSpoon\tile\Tile;

/**
 * Class Hopper
 * @package Xenophilicy\TableSpoon\block
 */
class Hopper extends Transparent {
    protected $id = self::HOPPER_BLOCK;
    
    /**
     * Hopper constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }
    
    public function canBeActivated(): bool{
        return true;
    }
    
    public function getToolType(): int{
        return BlockToolType::TYPE_PICKAXE;
    }
    
    public function getName(): string{
        return "Hopper";
    }
    
    public function getHardness(): float{
        return 3;
    }
    
    public function getBlastResistance(): float{
        return 24;
    }
    
    public function onActivate(Item $item, Player $player = null): bool{
        if(TableSpoon::$settings["blocks"]["hoppers"]){
            if($player instanceof Player){
                $t = $this->getLevel()->getTile($this);
                if($t instanceof HopperTile){
                    if($player->isCreative() and TableSpoon::$settings["player"]["limited-creative"]){
                        return true;
                    }
                    $player->addWindow($t->getInventory());
                }else{
                    $nbt = new CompoundTag("", [new ListTag("Items", []), new StringTag("id", Tile::HOPPER), new IntTag("x", $this->x), new IntTag("y", $this->y), new IntTag("z", $this->z)]);
                    /** @var HopperTile $t */
                    $t = Tile::createTile(Tile::HOPPER, $this->getLevel(), $nbt);
                    if($player->isCreative() and TableSpoon::$settings["player"]["limited-creative"]){
                        return true;
                    }
                    $player->addWindow($t->getInventory());
                }
            }
        }
        return true;
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        $faces = [0 => 0, 1 => 0, 2 => 3, 3 => 2, 4 => 5, 5 => 4];
        $this->meta = $faces[$face];
        $this->getLevel()->setBlock($blockReplace, $this, true, true);
        
        $nbt = new CompoundTag("", [new ListTag("Items", []), new StringTag("id", Tile::HOPPER), new IntTag("x", $this->x), new IntTag("y", $this->y), new IntTag("z", $this->z)]);
        
        if($item->hasCustomName()){
            $nbt->setString("CustomName", $item->getCustomName());
        }
        
        if($item->hasCustomBlockData()){
            foreach($item->getCustomBlockData() as $key => $v){
                $nbt->{$key} = $v;
            }
        }
        
        Tile::createTile(Tile::HOPPER, $this->getLevel(), $nbt);
        return true;
    }
    
    public function getDrops(Item $item): array{
        return [Item::get(Item::HOPPER, 0, 1)];
    }
}
