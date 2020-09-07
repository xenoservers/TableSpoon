<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\network;

use pocketmine\network\mcpe\protocol\PacketPool;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class PacketManager
 * @package Xenophilicy\TableSpoon\network
 */
class PacketManager {
    private static $initialized;
    
    public static function init(){
        if(!self::$initialized){
            self::$initialized = true;
            if(TableSpoon::$settings["blocks"]["anvils"] || TableSpoon::$settings["enchantments"]["enchantment-table"]){
                PacketPool::registerPacket(new CraftingDataPacket());
                PacketPool::registerPacket(new InventoryTransactionPacket());
            }
        }
    }
}