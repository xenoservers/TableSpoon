<?php

declare(strict_types=1);

// FYI: Event Priorities work this way: LOWEST -> LOW -> NORMAL -> HIGH -> HIGHEST -> MONITOR

namespace Xenophilicy\TableSpoon;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\{level\LevelLoadEvent, Listener};
use pocketmine\event\entity\{EntityDamageEvent, EntityTeleportEvent};
use pocketmine\event\player\{cheat\PlayerIllegalMoveEvent,
    PlayerDropItemEvent,
    PlayerGameModeChangeEvent,
    PlayerInteractEvent,
    PlayerItemHeldEvent,
    PlayerLoginEvent,
    PlayerQuitEvent,
    PlayerRespawnEvent};
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\Player as PMPlayer;
use pocketmine\plugin\Plugin;
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\utils\ArmorTypes;

/**
 * Class EventListener
 * @package Xenophilicy\TableSpoon
 */
class EventListener implements Listener{

    /** @var Plugin */
    public $plugin;

    /**
     * EventListener constructor.
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param LevelLoadEvent $ev
     *
     * @priority LOWEST
     */
    public function onLevelLoad(LevelLoadEvent $ev){
        $TEMPORARY_ENTITIES = [Entity::XP_ORB, Entity::LIGHTNING_BOLT,];
        LevelManager::init();
        $lvl = $ev->getLevel();
        $lvlWeather = TableSpoon::$weatherData[$lvl->getId()] = new Weather($lvl, 0);
        if(TableSpoon::$settings["weather"]["enabled"]){
            $lvlWeather->setCanCalculate(($lvl->getName() != TableSpoon::$settings["dimensions"]["nether"]["name"] && $lvl->getName() != TableSpoon::$settings["dimensions"]["end"]["name"]));
        }else{
            $lvlWeather->setCanCalculate(false);
        }
        foreach($lvl->getEntities() as $entity){
            if(in_array($entity::NETWORK_ID, $TEMPORARY_ENTITIES)){
                if(!$entity->isFlaggedForDespawn()){
                    $entity->flagForDespawn();
                }
            }
        }
        return;
    }

    /**
     * @param EntityDamageEvent $ev
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onDamage(EntityDamageEvent $ev){
        $v = $ev->getEntity();
        $session = null;
        if($v instanceof PMPlayer){
            $session = TableSpoon::getInstance()->getSessionById($v->getId());
        }
        if($ev->getCause() === EntityDamageEvent::CAUSE_FALL){
            if($session instanceof Session){
                if($session->isUsingElytra() || $v->getLevel()->getBlock($v->subtract(0, 1, 0))->getId() == Block::SLIME_BLOCK){
                    $ev->setCancelled(true);
                }
            }
        }
        return;
    }

    /**
     * @param PlayerRespawnEvent $ev
     *
     * @priority HIGHEST
     */
    public function onRespawn(PlayerRespawnEvent $ev){
        if($ev->getPlayer()->isOnFire()) $ev->getPlayer()->setOnFire(0);
    }

    /**
     * @param PlayerLoginEvent $ev
     *
     * @priority LOWEST
     */
    public function onLogin(PlayerLoginEvent $ev){
        TableSpoon::getInstance()->createSession($ev->getPlayer());
    }

    /**
     * @param PlayerQuitEvent $ev
     *
     * @priority LOWEST
     */
    public function onLeave(PlayerQuitEvent $ev){
        TableSpoon::getInstance()->destroySession($ev->getPlayer());
        unset(TableSpoon::$onPortal[$ev->getPlayer()->getId()]);
    }

    /**
     * @param PlayerIllegalMoveEvent $ev
     *
     * @priority LOWEST
     */
    public function onCheat(PlayerIllegalMoveEvent $ev){
        $session = TableSpoon::getInstance()->getSessionById($ev->getPlayer()->getId());
        if($session instanceof Session){
            if($session->allowCheats){
                $ev->setCancelled();
            }
        }
    }

    /**
     * @param PlayerItemHeldEvent $ev
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onItemHeld(PlayerItemHeldEvent $ev){
        $session = TableSpoon::getInstance()->getSessionById($ev->getPlayer()->getId());
        if($session instanceof Session){
            if($session->fishing){
                if($ev->getSlot() != $session->lastHeldSlot){
                    $session->unsetFishing();
                }
            }
            $session->lastHeldSlot = $ev->getSlot();
        }
    }

    /**
     * @param PlayerInteractEvent $ev
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onInteract(PlayerInteractEvent $ev){
        if(TableSpoon::$settings["player"]["instant-armor"]["enabled"]){
            $item = clone $ev->getItem();
            $player = $ev->getPlayer();
            $check = ($ev->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK || $ev->getAction() == PlayerInteractEvent::RIGHT_CLICK_AIR);
            $isBlocked = (in_array($ev->getBlock()->getId(), [Block::ITEM_FRAME_BLOCK,]));
            if($check && !$isBlocked){
                if($ev->getItem() instanceof Armor){
                    $inventory = $player->getArmorInventory();
                    $type = ArmorTypes::getType($item);
                    $old = Item::get(Item::AIR, 0, 1);
                    $skipReplace = false;
                    if($type !== ArmorTypes::TYPE_NULL){
                        switch($type){
                            case ArmorTypes::TYPE_HELMET:
                                $old = clone $inventory->getHelmet();
                                if(!TableSpoon::$settings["player"]["instant-armor"]["replace"] && !$old->isNull()){
                                    $skipReplace = true;
                                    break;
                                }
                                $inventory->setHelmet($item);
                                break;
                            case ArmorTypes::TYPE_CHESTPLATE:
                                $old = clone $inventory->getChestplate();
                                if(!TableSpoon::$settings["player"]["instant-armor"]["replace"] && !$old->isNull()){
                                    $skipReplace = true;
                                    break;
                                }
                                $inventory->setChestplate($item);
                                break;
                            case ArmorTypes::TYPE_LEGGINGS:
                                $old = clone $inventory->getLeggings();
                                if(!TableSpoon::$settings["player"]["instant-armor"]["replace"] && !$old->isNull()){
                                    $skipReplace = true;
                                    break;
                                }
                                $inventory->setLeggings($item);
                                break;
                            case ArmorTypes::TYPE_BOOTS:
                                $old = clone $inventory->getBoots();
                                if(!TableSpoon::$settings["player"]["instant-armor"]["replace"] && !$old->isNull()){
                                    $skipReplace = true;
                                    break;
                                }
                                $inventory->setBoots($item);
                                break;
                        }
                        if(!$skipReplace){
                            if(!TableSpoon::$settings["player"]["instant-armor"]["replace"]){
                                if($player->isSurvival() || $player->isAdventure()){
                                    $player->getInventory()->setItemInHand(Item::get(Item::AIR, 0, 1));
                                }
                            }else{
                                if(!$old->isNull()){
                                    $player->getInventory()->setItemInHand($old);
                                }else{
                                    $player->getInventory()->setItemInHand(Item::get(Item::AIR, 0, 1));
                                }
                            }
                        }
                    }
                }
            }
        }
        /*if($ev->getItem() instanceof BlazeRod && $ev->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK){
          $ev->setCancelled();
          ($player = $ev->getPlayer())->getLevel()->setBlockDataAt(($blockClicked = $ev->getBlock())->x, $blockClicked->y, $blockClicked->z, ($player->getLevel()->getBlock($blockClicked)->getDamage() + 1) % 16);
        }*/
    }

    /**
     * @param PlayerGameModeChangeEvent $ev
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onGameModeChange(PlayerGameModeChangeEvent $ev){
        if(TableSpoon::$settings["player"]["clear-inv"]){
            $ev->getPlayer()->getInventory()->clearAll();
        }
    }

    /**
     * @param PlayerDropItemEvent $ev
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerDropItem(PlayerDropItemEvent $ev){
        if(TableSpoon::$settings["player"]["limited-creative"] && $ev->getPlayer()->isCreative()){
            $ev->setCancelled();
        }
    }

    /**
     * @param EntityTeleportEvent $ev
     *
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onTeleport(EntityTeleportEvent $ev){
        $frLvl = ($from = $ev->getFrom())->getLevel();
        $toLvl = ($to = $ev->getTo())->getLevel();
        if((TableSpoon::$settings["dimensions"]["nether"]["enabled"] || TableSpoon::$settings["dimensions"]["end"]["enabled"]) && $frLvl instanceof Level && $toLvl instanceof Level && $frLvl !== $toLvl){
            if(Utils::getDimension($frLvl) != ($dim = Utils::getDimension($toLvl))){
                $p = $ev->getEntity();
                if($p instanceof PMPlayer){
                    $pk = new ChangeDimensionPacket();
                    $pk->dimension = $dim;
                    $pk->position = $to;
                    $pk->respawn = false;
                    $p->sendDataPacket($pk);
                    $p->sendPlayStatus(PlayStatusPacket::PLAYER_SPAWN);
                }
            }
        }
    }
}
