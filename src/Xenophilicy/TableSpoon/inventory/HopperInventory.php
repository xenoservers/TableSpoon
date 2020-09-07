<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;


use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use Xenophilicy\TableSpoon\tile\Hopper;

/**
 * Class HopperInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class HopperInventory extends ContainerInventory {
    /**
     * HopperInventory constructor.
     * @param Hopper $tile
     */
    public function __construct(Hopper $tile){
        parent::__construct($tile);
    }
    
    /**
     * @return Vector3
     */
    public function getHolder(){
        return $this->holder;
    }
    
    public function getDefaultSize(): int{
        return 5;
    }
    
    public function getNetworkType(): int{
        return WindowTypes::HOPPER;
    }
    
    public function getName(): string{
        return "Hopper";
    }
}