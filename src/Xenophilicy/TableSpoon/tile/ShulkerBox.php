<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\tile;

use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\nbt\tag\CompoundTag;
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
    
    /** @var ShulkerBoxInventory */
    protected $inventory;
    
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
    
    protected function readSaveData(CompoundTag $nbt): void{
        $this->loadName($nbt);
        $this->inventory = new ShulkerBoxInventory($this);
        $this->loadItems($nbt);
    }
    
    protected function writeSaveData(CompoundTag $nbt): void{
        $this->saveName($nbt);
        $this->saveItems($nbt);
    }
}