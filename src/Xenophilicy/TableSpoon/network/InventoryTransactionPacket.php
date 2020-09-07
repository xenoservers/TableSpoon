<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\network;

use pocketmine\network\mcpe\protocol\InventoryTransactionPacket as PMInventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use Xenophilicy\TableSpoon\network\types\NetworkInventoryAction;

/**
 * Class InventoryTransactionPacket
 * @package Xenophilicy\TableSpoon\network
 */
class InventoryTransactionPacket extends PMInventoryTransactionPacket {
    
    /** @var bool */
    public $isCraftingPart;
    
    /** @var bool */
    public $isFinalCraftingPart;
    
    protected function decodePayload(): void{
        parent::decodePayload();
        foreach($this->actions as $index => $action){
            $this->actions[$index] = NetworkInventoryAction::cast($action);
            if($action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER and $action->windowId === ContainerIds::UI and $action->inventorySlot === 50 and !$action->oldItem->equalsExact($action->newItem)){
                $this->isCraftingPart = true;
                if(!$action->oldItem->isNull() and $action->newItem->isNull()){
                    $this->isFinalCraftingPart = true;
                }
            }elseif($action->sourceType === NetworkInventoryAction::SOURCE_TODO and ($action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_RESULT or $action->windowId === NetworkInventoryAction::SOURCE_TYPE_CRAFTING_USE_INGREDIENT)){
                $this->isCraftingPart = true;
            }
        }
    }
}