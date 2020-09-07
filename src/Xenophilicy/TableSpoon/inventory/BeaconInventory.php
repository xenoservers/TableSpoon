<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use Xenophilicy\TableSpoon\tile\Beacon;

/**
 * Class BeaconInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class BeaconInventory extends ContainerInventory {
    /**
     * BeaconInventory constructor.
     * @param Beacon $tile
     */
    public function __construct(Beacon $tile){
        parent::__construct($tile);
    }
    
    public function getNetworkType(): int{
        return WindowTypes::BEACON;
    }
    
    public function getName(): string{
        return "Beacon";
    }
    
    public function getDefaultSize(): int{
        return 1;
    }
    
    /**
     * @return Vector3
     */
    public function getHolder(){
        return $this->holder;
    }
}