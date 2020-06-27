<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use Xenophilicy\TableSpoon\item\enchantment\Enchantment;

/**
 * Class Mooshroom
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Mooshroom extends Animal {
    
    public const NETWORK_ID = self::MOOSHROOM;
    
    public $width = 0.9;
    public $height = 1.4;
    
    public function getName(): string{
        return "Mooshroom";
    }
    
    public function getDrops(): array{
        $lootingL = 0;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $dmg = $cause->getDamager();
            if($dmg instanceof Player){
                $lootingL = $dmg->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::LOOTING);
            }
        }
        return [Item::get(Item::RAW_BEEF, 0, mt_rand(1, 3 + $lootingL)), Item::get(Item::LEATHER, 0, mt_rand(0, 2 + $lootingL)),];
    }
}
