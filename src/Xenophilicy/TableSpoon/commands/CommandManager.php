<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;

use pocketmine\command\Command;
use pocketmine\Server as PMServer;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class CommandManager
 * @package Xenophilicy\TableSpoon\commands
 */
class CommandManager {
    public static function init(){
        $cmds = [new WorldCommand("world"), new ClearCommand("clear"), new PlaySoundCommand("playsound")];
        if(TableSpoon::$settings["weather"]["enabled"]){
            $cmds[] = new WeatherCommand("weather");
        }
        PMServer::getInstance()->getCommandMap()->registerAll("pocketmine", $cmds);
        self::overwrite(new KillCommand("kill"));
    }
    
    /**
     * @param Command $cmd
     */
    public static function overwrite(Command $cmd){
        $cmdMap = PMServer::getInstance()->getCommandMap();
        $cmdMap->unregister($cmdMap->getCommand($cmd->getName()));
        
        $cmdMap->register("pocketmine", $cmd);
    }
}
