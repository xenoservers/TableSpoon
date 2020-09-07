<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\handlers;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\item\Axe;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\TieredTool;
use pocketmine\Player as PMPlayer;
use Xenophilicy\TableSpoon\item\enchantment\Enchantment;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class EnchantHandler
 * @package Xenophilicy\TableSpoon\handlers
 */
class EnchantHandler implements Listener {
    /**
     * TODO:
     *  - [X] Smite
     *  - [X] Bane of athropods
     *  - [X] Looting
     *  - [X] Fortune
     *  - [X] Luck of the sea
     *  - [X] Lure
     *  - [ ] Frost walker (Very laggy as of now)
     */
    
    /** @var string */
    public const BANE_OF_ARTHROPODS_AFFECTED_ENTITIES = [ // Based on https://minecraft.gamepedia.com/Enchanting#Bane_of_Arthropods ^_^
      "Spider", "Cave Spider", "Silverfish", "Endermite"];
    
    /**
     * @param EntityDamageEvent $ev
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onDamage(EntityDamageEvent $ev){
        $e = $ev->getEntity();
        if($ev instanceof EntityDamageByEntityEvent){
            $d = $ev->getDamager();
            if(!($d instanceof Entity) || !$d->isAlive()){
                return;
            }
            if($d instanceof PMPlayer && $e instanceof Living){
                $i = $d->getInventory()->getItemInHand();
                $damage = $ev->getModifier(EntityDamageEvent::MODIFIER_ARMOR);
                foreach(Utils::getEnchantments($i) as $ench){
                    $lvl = $ench->getLevel();
                    switch($ench->getId()){
                        case Enchantment::BANE_OF_ARTHROPODS:
                            if(Utils::in_arrayi($e->getName(), self::BANE_OF_ARTHROPODS_AFFECTED_ENTITIES)){
                                $ev->setModifier($damage + ($lvl * 2.5), EntityDamageEvent::MODIFIER_ARMOR);
                            }
                            break;
                        case Enchantment::SMITE:
                            break;
                    }
                }
            }
        }
    }
    
    /**
     * @param BlockBreakEvent $ev
     * Attribution:
     *  - Big thanks to @TheAz928 for the values... It really helped a lot! :D
     *  - The onBreak function below is a refactored, bare-bones and more-human friendly version of his Fortune enchant handler...
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onBreak(BlockBreakEvent $ev){
        $block = $ev->getBlock();
        $item = $ev->getItem();
        $fortuneEnchantment = $item->getEnchantment(Enchantment::FORTUNE);
        if($fortuneEnchantment instanceof EnchantmentInstance){
            $level = $fortuneEnchantment->getLevel() + 1;
            $rand = rand(1, $level);
            if($item instanceof TieredTool){
                switch($block->getId()){
                    case Block::COAL_ORE:
                        if($item instanceof Pickaxe){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), $rand));
                        }
                        break;
                    case Block::LAPIS_ORE:
                        if($item instanceof Pickaxe && $item->getTier() > TieredTool::TIER_WOODEN){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(0, 3) + $rand));
                        }
                        break;
                    case Block::GLOWING_REDSTONE_ORE:
                    case Block::REDSTONE_ORE:
                        if($item instanceof Pickaxe && $item->getTier() > TieredTool::TIER_WOODEN){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(1, 2) + $rand));
                        }
                        break;
                    case Block::NETHER_QUARTZ_ORE:
                        if($item instanceof Pickaxe && $item->getTier() > TieredTool::TIER_WOODEN){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(0, 1) + $rand));
                        }
                        break;
                    case Block::DIAMOND_ORE:
                    case Block::EMERALD_ORE:
                        if($item instanceof Pickaxe && $item->getTier() >= TieredTool::TIER_IRON){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), $rand));
                        }
                        break;
                    case Block::CARROT_BLOCK:
                    case Block::POTATO_BLOCK:
                    case Block::BEETROOT_BLOCK:
                    case Block::WHEAT_BLOCK:
                        if($item instanceof Axe || $item instanceof Pickaxe){
                            if($block->getDamage() >= 7){
                                $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(1, 3) + $rand));
                            }
                        }
                        break;
                    case Block::MELON_BLOCK:
                        if($item instanceof Axe || $item instanceof Pickaxe){
                            $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(3, 9) + $rand));
                        }
                        break;
                    case Block::LEAVES:
                        if(rand(1, 100) <= 10 + $level * 2){
                            $ev->setDrops([Item::get(Item::APPLE, 0, 1)]);
                        }
                        break;
                }
            }
        }
    }
    
    /**
     * @param Item[] $drops
     * @param int $amount
     * @return Item[]
     */
    private function increaseDrops(array $drops, int $amount = 1){
        $newDrops = [];
        foreach($drops as $drop){
            $newDrops[] = $drop->setCount(1 + $amount);
        }
        return $newDrops;
    }
    
    /**
     * @param EntityDeathEvent $ev
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onEntityDeath(EntityDeathEvent $ev){
        $ent = $ev->getEntity();
        if(!($ent instanceof Human)){
            $cause = $ent->getLastDamageCause();
            if($cause instanceof EntityDamageByEntityEvent){
                $damager = $cause->getDamager();
                if($damager instanceof PMPlayer){
                    $enchantment = $damager->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                    if($enchantment instanceof EnchantmentInstance){
                        $ev->setDrops($this->increaseDrops($ev->getDrops(), rand(1, $enchantment->getLevel() + 1)));
                    }
                }
            }
        }
    }
}
