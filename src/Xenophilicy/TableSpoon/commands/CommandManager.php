<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;

use pocketmine\Server;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class CommandManager
 * @package Xenophilicy\TableSpoon\commands
 */
class CommandManager {
    
    public static function init(){
        $cmds = [new WorldCommand("world"), new PlaySoundCommand("playsound")];
        if(TableSpoon::$settings["weather"]["enabled"]){
            $cmds[] = new WeatherCommand("weather");
        }
        Server::getInstance()->getCommandMap()->registerAll("pocketmine", $cmds);
    }
}
