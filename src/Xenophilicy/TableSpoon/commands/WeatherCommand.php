<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class WeatherCommand
 * @package Xenophilicy\TableSpoon\commands
 */
class WeatherCommand extends VanillaCommand {
    
    /**
     * WeatherCommand constructor
     * @param string $name
     */
    public function __construct($name){
        parent::__construct($name, "Set the weather on a level");
        $this->setPermission("pocketmine.command.weather");
    }
    
    /**
     * @param CommandSender $sender
     * @param string $currentAlias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)) return false;
        if(count($args) === 1){
            if(!$sender instanceof Player){
                $sender->sendMessage(TF::RED . "You must specify a level");
                return false;
            }
            if($args[0] === "get"){
                $sender->sendMessage($this->getWeather(TableSpoon::$weatherData[$sender->getLevel()->getId()]->getWeather()));
                return true;
            }
            $this->applyWeather($sender->getLevel(), Weather::getWeatherFromString($args[0]), $sender);
        }elseif(count($args) === 2){
            $level = $sender->getServer()->getLevelByName($args[0]);
            if(!$level instanceof Level){
                $sender->sendMessage(TF::RED . "That world doesn't exist");
                return false;
            }
            if($args[1] === "get"){
                $sender->sendMessage($this->getWeather(TableSpoon::$weatherData[$level->getId()]->getWeather()));
                return true;
            }
            $this->applyWeather($level, Weather::getWeatherFromString($args[1]), $sender);
        }else{
            $sender->sendMessage(TF::RED . "Usage: /weather [level] <get|clear|sunny|rain|storm|thunder>");
            return false;
        }
        return true;
    }
    
    /**
     * @param int $weather
     * @return string
     */
    private function getWeather(int $weather): string{
        switch($weather){
            case 0:
                return TF::GREEN . "Weather: Clear";
            case 1:
                return TF::GREEN . "Weather: Rainy";
            case 2:
                return TF::GREEN . "Weather: Rainy Thunder";
            case 3:
                return TF::GREEN . "Weather: Thunder";
            default:
                return TF::YELLOW . "Weather: Unknown";
        }
    }
    
    /**
     * @param Level $level
     * @param int $weather
     * @param CommandSender $sender
     * @return void
     */
    private function applyWeather(Level $level, int $weather, CommandSender $sender){
        $min = TableSpoon::$settings["weather"]["duration"]["minimum"];
        $max = TableSpoon::$settings["weather"]["duration"]["maximum"];
        if(!isset($args[1])) $duration = mt_rand($min, $max);else $duration = (int)$args[1];
        if($weather >= 0 and $weather <= 3){
            TableSpoon::$weatherData[$level->getId()]->setWeather($weather, $duration);
            $sender->sendMessage(TF::GREEN . "Weather Successfully changed on " . $level->getName());
        }else{
            $sender->sendMessage(TF::RED . "Invalid Weather");
        }
    }
}
