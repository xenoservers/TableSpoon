<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level;

use pocketmine\level\generator\GeneratorManager;
use pocketmine\Server;
use Xenophilicy\TableSpoon\level\generator\ender\Ender;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class LevelManager
 * @package Xenophilicy\TableSpoon\level
 */
class LevelManager {
    public static $loaded = false;
    
    public static function init(){
        if(!self::$loaded){
            self::$loaded = true;
            self::registerGenerators();
            self::loadAndGenerateLevels();
        }
    }
    
    private static function registerGenerators(){
        if(TableSpoon::$settings["dimensions"]["end"]["enabled"]){
            GeneratorManager::addGenerator(Ender::class, "ender");
        }
    }
    
    private static function loadAndGenerateLevels(){
        TableSpoon::$overworldLevel = TableSpoon::getInstance()->getServer()->getDefaultLevel();
        if(TableSpoon::$settings["dimensions"]["nether"]["enabled"]){
            if(TableSpoon::$settings["dimensions"]["nether"]["generate"]){
                if(!Server::getInstance()->loadLevel(TableSpoon::$settings["dimensions"]["nether"]["name"])){
                    Server::getInstance()->generateLevel(TableSpoon::$settings["dimensions"]["nether"]["name"], (time()), GeneratorManager::getGenerator("nether"));
                }
            }
            TableSpoon::$netherLevel = Server::getInstance()->getLevelByName(TableSpoon::$settings["dimensions"]["nether"]["name"]);
        }
        if(TableSpoon::$settings["dimensions"]["end"]["enabled"]){
            if(TableSpoon::$settings["dimensions"]["end"]["generate"]){
                if(!Server::getInstance()->loadLevel(TableSpoon::$settings["dimensions"]["end"]["name"])){
                    Server::getInstance()->generateLevel(TableSpoon::$settings["dimensions"]["end"]["name"], time(), GeneratorManager::getGenerator("ender"));
                }
            }
            TableSpoon::$endLevel = Server::getInstance()->getLevelByName(TableSpoon::$settings["dimensions"]["end"]["name"]);
        }
    }
}
