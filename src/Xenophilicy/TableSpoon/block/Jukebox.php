<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use Xenophilicy\TableSpoon\item\Record;
use Xenophilicy\TableSpoon\tile\Jukebox as JukeboxTile;
use Xenophilicy\TableSpoon\tile\Tile;

/**
 * Class Jukebox
 * @package Xenophilicy\TableSpoon\block
 */
class Jukebox extends Solid {
    
    /** @var int $id */
    protected $id = self::JUKEBOX;
    
    /**
     * Jukebox constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        parent::__construct(self::JUKEBOX, $meta);
    }
    
    public function getName(): string{
        return "Jukebox";
    }
    
    public function getHardness(): float{
        return 2;
    }
    
    public function getToolType(): int{
        return BlockToolType::TYPE_AXE;
    }
    
    public function getToolHarvestLevel(): int{
        return TieredTool::TIER_WOODEN;
    }
    
    public function getDrops(Item $item): array{
        $drops = [];
        $drops[] = Item::get(Item::JUKEBOX, 0, 1);
        
        $tile = $this->getLevel()->getTile($this);
        if($tile instanceof JukeboxTile){
            $drops[] = $tile->getRecordItem();
        }
        return $drops;
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        $this->getLevel()->setBlock($blockReplace, $this, true, true);
        $tile = $this->getLevel()->getTile($this);
        if(!($tile instanceof JukeboxTile)){
            $nbt = new CompoundTag("", [new StringTag(Tile::TAG_ID, Tile::JUKEBOX), new IntTag(Tile::TAG_X, (int)$this->getX()), new IntTag(Tile::TAG_Y, (int)$this->getY()), new IntTag(Tile::TAG_Z, (int)$this->getZ())]);
            Tile::createTile(Tile::JUKEBOX, $this->getLevel(), $nbt);
        }
        return true;
    }
    
    public function onActivate(Item $item, Player $player = null): bool{
        $tile = $this->getLevel()->getTile($this);
        if($tile instanceof JukeboxTile){
            $tile->dropMusicDisc();
            if($item instanceof Record){
                $tile->setRecordItem($item);
                $tile->playMusicDisc();
                if($player != null){
                    $item->count--;
                }
            }
        }else{
            $nbt = new CompoundTag("", [new StringTag(Tile::TAG_ID, Tile::JUKEBOX), new IntTag(Tile::TAG_X, (int)$this->getX()), new IntTag(Tile::TAG_Y, (int)$this->getY()), new IntTag(Tile::TAG_Z, (int)$this->getZ())]);
            /** @var JukeboxTile $tile */
            $tile = Tile::createTile(Tile::JUKEBOX, $this->getLevel(), $nbt);
            
            if($item instanceof Record){
                $tile->setRecordItem($item);
                if($player != null){
                    $item->count--;
                }
            }
        }
        return true;
    }
}
