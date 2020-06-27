<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Xenophilicy\TableSpoon\block\BlockManager;
use Xenophilicy\TableSpoon\commands\CommandManager;
use Xenophilicy\TableSpoon\entity\EntityManager;
use Xenophilicy\TableSpoon\handlers\{EnchantHandler, PacketHandler};
use Xenophilicy\TableSpoon\inventory\BrewingManager;
use Xenophilicy\TableSpoon\item\{enchantment\Enchantment, ItemManager};
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\network\PacketManager;
use Xenophilicy\TableSpoon\task\TickLevelsTask;
use Xenophilicy\TableSpoon\tile\Tile;
use Xenophilicy\TableSpoon\utils\FishingLootTable;

/**
 * Class TableSpoon
 * @package Xenophilicy\TableSpoonX
 */
class TableSpoon extends PluginBase {
    
    /** @var Config */
    public static $cacheFile;
    /** @var int[] */
    public static $onPortal = [];
    /** @var Level */
    public static $netherLevel;
    /** @var Level */
    public static $endLevel;
    /** @var Weather[] */
    public static $weatherData = [];
    /** @var array */
    public static $settings;
    public static $overworldLevel;
    /** @var TableSpoon */
    private static $instance;
    /** @var Session[] */
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
        $this->initManagers();
        $this->checkConfigVersion();
    }
    
    private function initManagers(){
        CommandManager::init();
        Enchantment::init();
        BlockManager::init();
        ItemManager::init();
        EntityManager::init();
        Tile::init();
        FishingLootTable::init();
        PacketManager::init();
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
    
    private function checkConfigVersion(){
        $configVersion = $this->getConfig()->get("VERSION");
        $pluginVersion = $this->getDescription()->getVersion();
        if(version_compare("1.0.0", $configVersion, "gt")){
            $this->getLogger()->warning("You have updated TableSpoon to v" . $pluginVersion . " but have a config from v$configVersion! Please delete your old config for new features to be enabled and to prevent unwanted errors!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
    }
    
    public function onDisable(){
        self::$cacheFile->save();
    }
    
    public function createSession(Player $player): bool{
        if(!isset($this->sessions[$player->getId()])){
            $this->sessions[$player->getId()] = new Session($player);
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
     * @return Session|null
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
     * @return Session|null
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
