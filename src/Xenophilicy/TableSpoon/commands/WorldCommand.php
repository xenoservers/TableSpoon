<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;

use pocketmine\command\{CommandSender, defaults\VanillaCommand};
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

/**
 * Class WorldCommand
 * @package Xenophilicy\TableSpoon\commands
 */
class WorldCommand extends VanillaCommand {
    
    /**
     * WorldCommand constructor.
     * @param $name
     */
    public function __construct($name){
        parent::__construct($name, "Teleport to a world");
        $this->setPermission("pocketmine.command.world");
    }
    
    /**
     * @param CommandSender $sender
     * @param string $currentAlias
     * @param array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)) return false;
        if(!$sender instanceof Player){
            $sender->sendMessage(TF::RED . "This command must be executed as a player");
            return false;
        }
        if(count($args) === 1){
            $level = array_shift($args);
            $sender->getServer()->loadLevel($level);
            if(($level = $sender->getServer()->getLevelByName($level)) == null){
                $sender->sendMessage(TF::RED . "That world doesn't exist");
                return false;
            }
            $sender->teleport($level->getSpawnLocation());
            $sender->sendMessage(TF::GREEN . "Teleported to world " . TF::YELLOW . $level->getName());
            return true;
        }elseif(count($args) === 2){
            $name = array_shift($args);
            $level = array_shift($args);
            $sender->getServer()->loadLevel($level);
            if(($level = $sender->getServer()->getLevelByName($level)) == null){
                $sender->sendMessage(TF::RED . "That world does not exist");
                return false;
            }
            $player = $sender->getServer()->getPlayer($name);
            if($player === null){
                $sender->sendMessage(TF::RED . "That player isn't online");
                return false;
            }
            $player->teleport($level->getSpawnLocation());
            $player->sendMessage(TF::GREEN . "Teleported to world " . TF::YELLOW . $level->getName());
            $player->sendMessage(TF::GREEN . "Teleported player " . TF::AQUA . $player->getName() . TF::GREEN . " to world " . TF::YELLOW . $level->getName());
            return true;
        }else{
            $sender->sendMessage(TF::RED . "Usage: /world [player] <world>");
            return false;
        }
    }
}
