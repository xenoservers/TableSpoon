<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Anvil as PMAnvil;
use pocketmine\item\Item;
use pocketmine\Player;
use Xenophilicy\TableSpoon\inventory\AnvilInventory;
use Xenophilicy\TableSpoon\network\types\WindowIds;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class Anvil
 * @package Xenophilicy\TableSpoon\block
 */
class Anvil extends PMAnvil {
    public function onActivate(Item $item, Player $player = null): bool{
        if(TableSpoon::$settings["blocks"]["anvils"]){
            if($player instanceof Player){
                $player->addWindow(new AnvilInventory($this), WindowIds::ANVIL);
            }
        }
        return true;
    }
}