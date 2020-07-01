<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\level\generator\GeneratorManager;
use pocketmine\Server as PMServer;
use Xenophilicy\TableSpoon\level\generator\{ender\Ender};

/**
 * Class LevelManager
 * @package Xenophilicy\TableSpoon
 */
class LevelManager{
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
            if(!PMServer::getInstance()->loadLevel(TableSpoon::$settings["dimensions"]["nether"]["name"])){
                PMServer::getInstance()->generateLevel(TableSpoon::$settings["dimensions"]["nether"]["name"], time(), GeneratorManager::getGenerator("nether"));
            }
            TableSpoon::$netherLevel = PMServer::getInstance()->getLevelByName(TableSpoon::$settings["dimensions"]["nether"]["name"]);
        }
        if(TableSpoon::$settings["dimensions"]["end"]["enabled"]){
            if(!PMServer::getInstance()->loadLevel(TableSpoon::$settings["dimensions"]["end"]["name"])){
                PMServer::getInstance()->generateLevel(TableSpoon::$settings["dimensions"]["end"]["name"], time(), GeneratorManager::getGenerator("ender"));
            }
            TableSpoon::$endLevel = PMServer::getInstance()->getLevelByName(TableSpoon::$settings["dimensions"]["end"]["name"]);
        }
    }
}
