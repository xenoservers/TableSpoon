<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\network\types;

use pocketmine\inventory\transaction\action\CreativeInventoryAction;
use pocketmine\inventory\transaction\action\DropItemAction;
use pocketmine\inventory\transaction\action\InventoryAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction as PMNetworkInventoryAction;
use pocketmine\Player;
use UnexpectedValueException;
use Xenophilicy\TableSpoon\inventory\AnvilInventory;
use Xenophilicy\TableSpoon\inventory\EnchantInventory;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class NetworkInventoryAction
 * @package Xenophilicy\TableSpoon\network\types
 */
class NetworkInventoryAction extends PMNetworkInventoryAction {
    
    public const SOURCE_CONTAINER = 0;
    public const SOURCE_CRAFTING_GRID = 100;
    public const SOURCE_TODO = 99999;
    public const SOURCE_TYPE_CRAFTING_ADD_INGREDIENT = -2;
    public const SOURCE_TYPE_CRAFTING_REMOVE_INGREDIENT = -3;
    public const SOURCE_TYPE_ANVIL_INPUT = -10;
    public const SOURCE_TYPE_ANVIL_MATERIAL = -11;
    public const SOURCE_TYPE_ENCHANT_INPUT = -15;
    public const SOURCE_TYPE_ENCHANT_MATERIAL = -16;
    public const SOURCE_TYPE_CONTAINER_DROP_CONTENTS = -100;
    
    public static function cast(PMNetworkInventoryAction $action): self{
        $newAction = new self();
        $newAction->sourceType = $action->sourceType;
        $newAction->windowId = $action->windowId;
        $newAction->sourceFlags = $action->sourceFlags;
        $newAction->inventorySlot = $action->inventorySlot;
        $newAction->oldItem = clone $action->oldItem;
        $newAction->newItem = clone $action->newItem;
        $newAction->newItemStackId = $action->newItemStackId;
        return $newAction;
    }
    
    /**
     * @param NetworkBinaryStream $packet
     * @param bool $hasItemStackIds
     * @return $this|NetworkInventoryAction
     */
    public function read(NetworkBinaryStream $packet, bool $hasItemStackIds){
        try{
            parent::read($packet, $hasItemStackIds);
        }catch(UnexpectedValueException $exception){
            if($this->sourceType === self::SOURCE_CRAFTING_GRID){
                $this->windowId = $packet->getVarInt();
            }else{
                throw new UnexpectedValueException("Unknown inventory action source type $this->sourceType");
            }
        }
        
        return $this;
    }
    
    /**
     * @param Player $player
     * @return CreativeInventoryAction|DropItemAction|InventoryAction|SlotChangeAction|null
     */
    public function createInventoryAction(Player $player){
        $return = null;
        try{
            $return = parent::createInventoryAction($player);
        }catch(UnexpectedValueException $exception){
            if($this->sourceType === self::SOURCE_TODO){
                switch($this->windowId){
                    case self::SOURCE_TYPE_CRAFTING_ADD_INGREDIENT:
                    case self::SOURCE_TYPE_CRAFTING_REMOVE_INGREDIENT:
                    case self::SOURCE_TYPE_CONTAINER_DROP_CONTENTS:
                        return new SlotChangeAction($player->getCraftingGrid(), $this->inventorySlot, $this->oldItem, $this->newItem);
                    case self::SOURCE_TYPE_CRAFTING_RESULT:
                    case self::SOURCE_TYPE_CRAFTING_USE_INGREDIENT:
                        return null;
                    case self::SOURCE_TYPE_ENCHANT_INPUT:
                    case self::SOURCE_TYPE_ENCHANT_MATERIAL:
                    case self::SOURCE_TYPE_ENCHANT_OUTPUT:
                        $inv = $player->getWindow(WindowIds::ENCHANT);
                        if(!($inv instanceof EnchantInventory)){
                            TableSpoon::getInstance()->getLogger()->debug("Player " . $player->getName() . " has no open enchant inventory");
                            return null;
                        }
                        switch($this->windowId){
                            case self::SOURCE_TYPE_ENCHANT_INPUT:
                                $this->inventorySlot = 0;
                                $local = $inv->getItem(0);
                                if($local->equals($this->newItem, true, false)){
                                    $inv->setItem(0, $this->newItem);
                                }
                                break;
                            case self::SOURCE_TYPE_ENCHANT_MATERIAL:
                                $this->inventorySlot = 1;
                                $inv->setItem(1, $this->oldItem);
                                break;
                            case self::SOURCE_TYPE_ENCHANT_OUTPUT:
                                break;
                        }
                        return new SlotChangeAction($inv, $this->inventorySlot, $this->oldItem, $this->newItem);
                    case self::SOURCE_TYPE_BEACON:
                        $inv = $player->getWindow(WindowIds::BEACON);
                        if(!($inv instanceof EnchantInventory)){
                            TableSpoon::getInstance()->getLogger()->debug("Player " . $player->getName() . " has no open beacon inventory");
                            return null;
                        }
                        $this->inventorySlot = 0;
                        return new SlotChangeAction($inv, $this->inventorySlot, $this->oldItem, $this->newItem);
                    case self::SOURCE_TYPE_ANVIL_INPUT:
                    case self::SOURCE_TYPE_ANVIL_MATERIAL:
                    case self::SOURCE_TYPE_ANVIL_RESULT:
                    case self::SOURCE_TYPE_ANVIL_OUTPUT:
                        $inv = $player->getWindow(WindowIds::ANVIL);
                        if(!($inv instanceof AnvilInventory)){
                            TableSpoon::getInstance()->getLogger()->debug("Player " . $player->getName() . " has no open anvil inventory");
                            return null;
                        }
                        switch($this->windowId){
                            case self::SOURCE_TYPE_ANVIL_INPUT:
                                $this->inventorySlot = 0;
                                break;
                            case self::SOURCE_TYPE_ANVIL_MATERIAL:
                                $this->inventorySlot = 1;
                                break;
                            case self::SOURCE_TYPE_ANVIL_OUTPUT:
                                $inv->sendSlot(2, $inv->getViewers());
                                break;
                            case self::SOURCE_TYPE_ANVIL_RESULT:
                                $this->inventorySlot = 2;
                                $cost = $inv->getItem(2)->getNamedTag()->getInt("RepairCost", 1); // todo
                                if($player->isSurvival() && $player->getXpLevel() < $cost){
                                    return null;
                                }
                                $inv->clear(0);
                                if(!($material = $inv->getItem(1))->isNull()){
                                    $material = clone $material;
                                    $material->count -= 1;
                                    $inv->setItem(1, $material);
                                }
                                $inv->setItem(2, $this->oldItem, false);
                                if($player->isSurvival()){
                                    $player->subtractXpLevels($cost);
                                }
                        }
                        return new SlotChangeAction($inv, $this->inventorySlot, $this->oldItem, $this->newItem);
                }
            }else{
                throw $exception;
            }
        }
        return $return;
    }
}
