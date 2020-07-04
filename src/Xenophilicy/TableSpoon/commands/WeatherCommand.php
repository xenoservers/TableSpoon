<?php


namespace Xenophilicy\TableSpoon\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\lang\TranslationContainer;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Xenophilicy\TableSpoon\level\weather\Weather;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class WeatherCommand
 * @package Xenophilicy\TableSpoon\commands
 */
class WeatherCommand extends VanillaCommand{

    /**
     * WeatherCommand constructor.
     *
     * @param string $name
     */
    public function __construct($name){
        parent::__construct($name, "Changes the Weather", "/weather [level] < get | clear | sunny | rain | rainy_thunder | thunder >");
        $this->setPermission("pocketmine.command.weather");
    }

    /**
     * @param CommandSender $sender
     * @param string $currentAlias
     * @param array $args
     *
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        if(count($args) < 1){
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
            return false;
        }
        if($sender instanceof Player){
            if($args[0] == "get"){
                switch(TableSpoon::$weatherData[$sender->getLevel()->getId()]->getWeather()){
                    case 0:
                        $sender->sendMessage("Weather: Clear");
                        return true;
                    case 1:
                        $sender->sendMessage("Weather: Rainy");
                        return true;
                    case 2:
                        $sender->sendMessage("Weather: Rainy Thunder");
                        return true;
                    case 3:
                        $sender->sendMessage("Weather: Thunder");
                        return true;
                }
            }
            $wea = Weather::getWeatherFromString($args[0]);
            if(!isset($args[1])) $duration = mt_rand(min(TableSpoon::$settings["weather"]["min"], TableSpoon::$settings["weather"]["max"]), max(TableSpoon::$settings["weather"]["min"], TableSpoon::$settings["weather"]["max"]));else $duration = (int)$args[1];
            if($wea >= 0 and $wea <= 3){
                TableSpoon::$weatherData[$sender->getLevel()->getId()]->setWeather($wea, $duration);
                $sender->sendMessage("Weather Successfully changed on " . $sender->getLevel()->getName());
                return true;
            }else{
                $sender->sendMessage(TextFormat::RED . "Invalid Weather");
                return false;
            }
        }
        if(count($args) < 2){
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
            return false;
        }
        $level = $sender->getServer()->getLevelByName($args[0]);
        if(!$level instanceof Level){
            $sender->sendMessage(TextFormat::RED . "Couldn't find level: " . $args[0]);
            return false;
        }
        if($args[1] == "get"){
            switch(TableSpoon::$weatherData[$level->getId()]->getWeather()){
                case 0:
                    $sender->sendMessage("Weather: Clear");
                    return true;
                case 1:
                    $sender->sendMessage("Weather: Rainy");
                    return true;
                case 2:
                    $sender->sendMessage("Weather: Rainy Thunder");
                    return true;
                case 3:
                    $sender->sendMessage("Weather: Thunder");
                    return true;
            }
        }
        $wea = Weather::getWeatherFromString($args[1]);
        if(!isset($args[1])) $duration = mt_rand(min(TableSpoon::$settings["weather"]["min"], TableSpoon::$settings["weather"]["max"]), max(TableSpoon::$settings["weather"]["min"], TableSpoon::$settings["weather"]["max"]));else $duration = (int)$args[1];
        if($wea >= 0 and $wea <= 3){
            TableSpoon::$weatherData[$level->getId()]->setWeather($wea, $duration);
            $sender->sendMessage("Weather Successfully changed on " . $level->getName());
            return true;
        }else{
            $sender->sendMessage(TextFormat::RED . "Invalid Weather");
            return false;
        }
    }
}
