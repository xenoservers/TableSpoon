<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Xenophilicy\TableSpoon\block\BlockManager;
use Xenophilicy\TableSpoon\block\multiblock\MultiBlockFactory;
use Xenophilicy\TableSpoon\commands\CommandManager;
use Xenophilicy\TableSpoon\entity\EntityManager;
use Xenophilicy\TableSpoon\handlers\{EnchantHandler, PacketHandler};
use Xenophilicy\TableSpoon\inventory\BrewingManager;
use Xenophilicy\TableSpoon\item\{enchantment\Enchantment, ItemManager};
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\network\PacketManager;
use Xenophilicy\TableSpoon\player\PlayerSession;
use Xenophilicy\TableSpoon\player\PlayerSessionManager;
use Xenophilicy\TableSpoon\task\DelayedLevelLoadTask;
use Xenophilicy\TableSpoon\task\TickLevelsTask;
use Xenophilicy\TableSpoon\tile\Tile;
use Xenophilicy\TableSpoon\utils\FishingLootTable;

/**
 * Class TableSpoon
 * @package Xenophilicy\TableSpoon
 */
class TableSpoon extends PluginBase {
    
    public const CONFIG_VERSION = "1.1.0";
    
    /** @var Config */
    public static $cacheFile;
    /** @var Level */
    public static $netherLevel;
    /** @var Level */
    public static $endLevel;
    /** @var Weather[] */
    public static $weatherData = [];
    /** @var array */
    public static $settings;
    /** @var Level */
    public static $overworldLevel;
    /** @var TableSpoon */
    private static $instance;
    /** @var PlayerSession[] */
    private $sessions = [];
    /** @var BrewingManager */
    private $brewingManager = null;
    
    public static function getInstance(): TableSpoon{
        return self::$instance;
    }
    
    public function onLoad(){
        $this->getLogger()->info("Loading Resources...");
        $this->saveDefaultConfig();
        self::$cacheFile = new Config($this->getDataFolder() . "cache.json", Config::JSON);
        self::$settings = $this->getConfig()->getAll();
        self::$instance = $this;
    }
    
    public function onEnable(){
        $this->checkConfigVersion();
        $this->initManagers();
    }
    
    private function checkConfigVersion(){
        if(version_compare(self::CONFIG_VERSION, $this->getConfig()->get("VERSION"), "gt")){
            $this->getLogger()->warning("You've updated TableSpoon but have an outdated config! Please delete your old config for new features to be enabled and to prevent unwanted errors!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
    }
    
    private function initManagers(){
        $this->getScheduler()->scheduleTask(new DelayedLevelLoadTask());
        PlayerSessionManager::init();
        CommandManager::init();
        Enchantment::init();
        BlockManager::init();
        ItemManager::init();
        EntityManager::init();
        Tile::init();
        FishingLootTable::init();
        PacketManager::init();
        MultiBlockFactory::init();
        $this->brewingManager = new BrewingManager();
        $this->brewingManager->init();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PacketHandler($this), $this);
        if(self::$settings["enchantments"]["vanilla"]){
            $this->getServer()->getPluginManager()->registerEvents(new EnchantHandler(), $this);
        }
        if(self::$settings["weather"]["enabled"]){
            $this->getScheduler()->scheduleRepeatingTask(new TickLevelsTask(), 1);
        }
    }
    
    public function onDisable(){
        self::$cacheFile->save();
    }
    
    public function createSession(Player $player): bool{
        if(!isset($this->sessions[$player->getId()])){
            $this->sessions[$player->getId()] = new PlayerSession($player);
            return true;
        }
        return false;
    }
    
    public function destroySession(Player $player): bool{
        if(isset($this->sessions[$player->getId()])){
            unset($this->sessions[$player->getId()]);
            return true;
        }
        return false;
    }
    
    /**
     * @param int $id
     * @return PlayerSession|null
     */
    public function getSessionById(int $id){
        if(isset($this->sessions[$id])){
            return $this->sessions[$id];
        }else{
            return null;
        }
    }
    
    /**
     * @param string $name
     * @return PlayerSession|null
     */
    public function getSessionByName(string $name){
        foreach($this->sessions as $session){
            if($session->getPlayer()->getName() == $name){
                return $session;
            }
        }
        return null;
    }
    
    public function getBrewingManager(): BrewingManager{
        return $this->brewingManager;
    }
}
