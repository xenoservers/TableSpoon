<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;

use pocketmine\inventory\AnvilInventory as PMAnvilInventory;
use pocketmine\Player;

/**
 * Class AnvilInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class AnvilInventory extends PMAnvilInventory {
    
    public function getDefaultSize(): int{
        return 3;
    }
    
    public function onClose(Player $who): void{
        foreach($this->getContents() as $item){
            foreach($who->getInventory()->addItem($item) as $doesntFit){
                $who->getLevel()->dropItem($this->holder, $doesntFit);
            }
        }
    }
}
