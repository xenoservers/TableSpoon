<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\tile;

use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Nameable;
use pocketmine\tile\NameableTrait;
use pocketmine\tile\Spawnable;
use Xenophilicy\TableSpoon\inventory\ShulkerBoxInventory;

/**
 * Class ShulkerBox
 * @package Xenophilicy\TableSpoon\tile
 */
class ShulkerBox extends Spawnable implements InventoryHolder, Container, Nameable {
    use NameableTrait, ContainerTrait;
    
    protected $facing = self::SIDE_UP;
    /** @var ShulkerBoxInventory */
    protected $inventory;
    
    /**
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
    }
    
    protected static function createAdditionalNBT(CompoundTag $nbt, Vector3 $pos, ?int $face = null, ?Item $item = null, ?Player $player = null): void{
        if($face === null){
            $face = 1;
        }
        $nbt->setByte("facing", $face);
        $nbt->setByte("isMovable", 1);
        $nbt->setByte("Findable", 0);
    }
    
    public function getDefaultName(): string{
        return "Shulker Box";
    }
    
    public function close(): void{
        if(!$this->isClosed()){
            $this->inventory->removeAllViewers(true);
            $this->inventory = null;
            parent::close();
        }
    }
    
    /**
     * @return Inventory|ShulkerBoxInventory
     */
    public function getRealInventory(){
        return $this->inventory;
    }
    
    /**
     * @return Inventory|ShulkerBoxInventory
     */
    public function getInventory(){
        return $this->inventory;
    }
    
    /**
     * @return int
     */
    public function getFacing(): int{
        return $this->facing;
    }
    
    protected function addAdditionalSpawnData(CompoundTag $nbt): void{
        $nbt->setByte("facing", $this->facing);
        $nbt->setByte("isMovable", 1);
        $nbt->setByte("Findable", 0);
    }
    
    protected function readSaveData(CompoundTag $nbt): void{
        $this->loadName($nbt);
        $this->inventory = new ShulkerBoxInventory($this);
        $this->loadItems($nbt);
        $this->facing = $nbt->getByte("facing", 1);
    }
    
    protected function writeSaveData(CompoundTag $nbt): void{
        $this->saveName($nbt);
        $this->saveItems($nbt);
        $nbt->setByte("facing", $this->facing);
        $nbt->setByte("isMovable", 1);
        $nbt->setByte("Findable", 0);
    }
}