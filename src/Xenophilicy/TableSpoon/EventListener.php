<?php
declare(strict_types=1);

// FYI: Event Priorities work this way: LOWEST -> LOW -> NORMAL -> HIGH -> HIGHEST -> MONITOR

namespace Xenophilicy\TableSpoon;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\{block\BlockBreakEvent, block\BlockPlaceEvent, Cancellable, level\LevelLoadEvent, Listener, server\DataPacketReceiveEvent,
  server\DataPacketSendEvent};
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent, EntityTeleportEvent};
use pocketmine\event\player\{cheat\PlayerIllegalMoveEvent, PlayerDropItemEvent, PlayerGameModeChangeEvent, PlayerInteractEvent, PlayerItemConsumeEvent,
  PlayerItemHeldEvent, PlayerLoginEvent, PlayerMoveEvent, PlayerQuitEvent, PlayerRespawnEvent};
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\SpawnSettings;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\player\PlayerSession;
use Xenophilicy\TableSpoon\player\PlayerSessionManager;
use Xenophilicy\TableSpoon\utils\ArmorTypes;

/**
 * Class EventListener
 * @package Xenophilicy\TableSpoon
 */
class EventListener implements Listener {
    
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
     * @param PlayerLoginEvent $event
     * @priority MONITOR
     */
    public function onPlayerLogin(PlayerLoginEvent $event): void{
        PlayerSessionManager::create($event->getPlayer());
    }
    
    /**
     * @param PlayerQuitEvent $event
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void{
        PlayerSessionManager::destroy($event->getPlayer());
    }
    
    /**
     * @param LevelLoadEvent $ev
     * @priority LOWEST
     */
    public function onLevelLoad(LevelLoadEvent $ev){
        $level = $ev->getLevel();
        $TEMPORARY_ENTITIES = [Entity::XP_ORB, Entity::LIGHTNING_BOLT];
        $lvlWeather = TableSpoon::$weatherData[$level->getId()] = new Weather($level, 0);
        if(TableSpoon::$settings["weather"]["enabled"]){
            $lvlWeather->setCanCalculate(($level->getName() != TableSpoon::$settings["dimensions"]["nether"]["name"] && $level->getName() != TableSpoon::$settings["dimensions"]["end"]["name"]));
        }else{
            $lvlWeather->setCanCalculate(false);
        }
        foreach($level->getEntities() as $entity){
            if(in_array($entity::NETWORK_ID, $TEMPORARY_ENTITIES)){
                if(!$entity->isFlaggedForDespawn()){
                    $entity->flagForDespawn();
                }
            }
        }
    }
    
    /**
     * @param EntityDamageEvent $ev
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onDamage(EntityDamageEvent $ev){
        $v = $ev->getEntity();
        $session = null;
        if($v instanceof Player){
            $session = TableSpoon::getInstance()->getSessionById($v->getId());
        }
        if($ev->getCause() === EntityDamageEvent::CAUSE_FALL){
            if($session instanceof PlayerSession){
                if($session->isUsingElytra() || $v->getLevel()->getBlock($v->subtract(0, 1, 0))->getId() == Block::SLIME_BLOCK){
                    $ev->setCancelled(true);
                }
            }
        }
    }
    
    /**
     * @param PlayerRespawnEvent $ev
     * @priority HIGHEST
     */
    public function onRespawn(PlayerRespawnEvent $ev){
        if($ev->getPlayer()->isOnFire()) $ev->getPlayer()->extinguish();
    }
    
    /**
     * @param PlayerLoginEvent $ev
     * @priority LOWEST
     */
    public function onLogin(PlayerLoginEvent $ev){
        TableSpoon::getInstance()->createSession($ev->getPlayer());
    }
    
    /**
     * @param PlayerQuitEvent $ev
     * @priority LOWEST
     */
    public function onLeave(PlayerQuitEvent $ev){
        TableSpoon::getInstance()->destroySession($ev->getPlayer());
    }
    
    /**
     * @param PlayerIllegalMoveEvent $ev
     * @priority LOWEST
     */
    public function onCheat(PlayerIllegalMoveEvent $ev){
        $session = TableSpoon::getInstance()->getSessionById($ev->getPlayer()->getId());
        if($session instanceof PlayerSession){
            if($session->allowCheats){
                $ev->setCancelled();
            }
        }
    }
    
    /**
     * @param PlayerItemHeldEvent $ev
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onItemHeld(PlayerItemHeldEvent $ev){
        $session = TableSpoon::getInstance()->getSessionById($ev->getPlayer()->getId());
        if($session instanceof PlayerSession){
            if($session->fishing){
                if($ev->getSlot() != $session->lastHeldSlot){
                    $session->unsetFishing();
                }
            }
            $session->lastHeldSlot = $ev->getSlot();
        }
    }
    
    /**
     * @param PlayerInteractEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onInteract(PlayerInteractEvent $event){
        if(!TableSpoon::$settings["player"]["instant-armor"]) return;
        $item = $event->getItem();
        $player = $event->getPlayer();
        $check = ($event->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK || $event->getAction() == PlayerInteractEvent::RIGHT_CLICK_AIR);
        $isBlocked = (in_array($event->getBlock()->getId(), [Block::ITEM_FRAME_BLOCK]));
        if(!$check || $isBlocked) return;
        if(!$event->getItem() instanceof Armor) return;
        $inventory = $player->getArmorInventory();
        $type = ArmorTypes::getType($item);
        $event->setCancelled();
        switch($type){
            case ArmorTypes::TYPE_HELMET:
                $old = $inventory->getHelmet();
                $inventory->setHelmet($item);
                break;
            case ArmorTypes::TYPE_CHESTPLATE:
                $old = $inventory->getChestplate();
                $inventory->setChestplate($item);
                break;
            case ArmorTypes::TYPE_LEGGINGS:
                $old = $inventory->getLeggings();
                $inventory->setLeggings($item);
                break;
            case ArmorTypes::TYPE_BOOTS:
                $old = $inventory->getBoots();
                $inventory->setBoots($item);
                break;
            default:
                return;
        }
        if($old->isNull()){
            $player->getInventory()->setItemInHand(Item::get(Item::AIR, 0, 1));
        }else{
            $player->getInventory()->setItemInHand($old);
        }
    }
    
    /**
     * @param PlayerGameModeChangeEvent $ev
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
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerDropItem(PlayerDropItemEvent $ev){
        if(TableSpoon::$settings["player"]["limited-creative"] && $ev->getPlayer()->isCreative()){
            $ev->setCancelled();
        }
    }
    
    /**
     * @param DataPacketSendEvent $event
     * @priority NORMAL
     */
    public function onDataPacketSend(DataPacketSendEvent $event): void{
        $packet = $event->getPacket();
        if(!$packet instanceof StartGamePacket) return;
        $target = $event->getPlayer();
        $world = $target->getLevel();
        if($world === null) return;
        $dimensionId = Utils::getDimension($world);
        if($dimensionId === $packet->spawnSettings->getDimension()) return;
        $event->setCancelled();
        $target->sendDataPacket(clone $packet);
        $pk = clone $packet;
        $pk->spawnSettings = new SpawnSettings($packet->spawnSettings->getBiomeType(), $packet->spawnSettings->getBiomeName(), Utils::getDimension($world));
        $target->sendDataPacket($pk);
    }
    
    /**
     * @param DataPacketReceiveEvent $event
     * @priority MONITOR
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event): void{
        $packet = $event->getPacket();
        // ACTION_DIMENSION_CHANGE_ACK doesn't get sent back for some reason????
        if(!$packet instanceof PlayerActionPacket || $packet->action !== PlayerActionPacket::ACTION_RESPAWN) return;
        $player = $event->getPlayer();
        if($player === null || !$player->isOnline()) return;
        PlayerSessionManager::get($player)->endDimensionChange();
    }
    
    /**
     * @param EntityTeleportEvent $event
     * @priority MONITOR
     */
    public function onEntityTeleport(EntityTeleportEvent $event): void{
        $player = $event->getEntity();
        if($player instanceof Player){
            $from = $event->getFrom()->getLevel();
            $to = $event->getTo()->getLevel();
            if(Utils::getDimension($from) === ($dim = Utils::getDimension($to))) return;
            $packet = new ChangeDimensionPacket();
            $packet->dimension = $dim;
            $packet->position = $event->getTo()->asVector3();
            $packet->respawn = !$player->isAlive();
            $player->sendDataPacket($packet);
            PlayerSessionManager::get($player)->startDimensionChange();
        }
    }
    
    /**
     * @param EntityDamageEvent $event
     * @priority LOW
     */
    public function onEntityDamage(EntityDamageEvent $event): void{
        $entity = $event->getEntity();
        if($entity instanceof Player && $this->checkDimChange($entity, $event)){
            return;
        }
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($damager instanceof Player){
                $this->checkDimChange($damager, $event);
            }
        }
    }
    
    private function checkDimChange(Player $player, Cancellable $event): bool{
        $instance = PlayerSessionManager::get($player);
        if($instance !== null && $instance->isChangingDimension()){
            $event->setCancelled();
            return true;
        }
        return false;
    }
    
    /**
     * @param PlayerInteractEvent $event
     * @priority LOW
     */
    public function onPlayerInteract(PlayerInteractEvent $event): void{
        $this->checkDimChange($event->getPlayer(), $event);
    }
    
    /**
     * @param PlayerItemConsumeEvent $event
     * @priority LOW
     */
    public function onPlayerItemUse(PlayerItemConsumeEvent $event): void{
        $this->checkDimChange($event->getPlayer(), $event);
    }
    
    /**
     * @param PlayerMoveEvent $event
     * @priority LOW
     */
    public function onPlayerMove(PlayerMoveEvent $event): void{
        $this->checkDimChange($event->getPlayer(), $event);
    }
    
    /**
     * @param BlockPlaceEvent $event
     * @priority LOW
     */
    public function onBlockPlace(BlockPlaceEvent $event): void{
        $this->checkDimChange($event->getPlayer(), $event);
    }
    
    /**
     * @param BlockBreakEvent $event
     * @priority LOW
     */
    public function onBlockBreak(BlockBreakEvent $event): void{
        $this->checkDimChange($event->getPlayer(), $event);
    }
}
