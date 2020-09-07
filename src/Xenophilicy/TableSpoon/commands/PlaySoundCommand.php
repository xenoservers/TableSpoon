<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;

use pocketmine\command\{CommandSender, defaults\VanillaCommand};
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;

/**
 * Class PlaySoundCommand
 * @package Xenophilicy\TableSpoon\commands
 */
class PlaySoundCommand extends VanillaCommand {
    /**
     * PlaySoundCommand constructor.
     * @param $name
     */
    public function __construct($name){
        parent::__construct($name, "Plays a sound");
        $this->setPermission("pocketmine.command.playsound");
    }
    
    /**
     * @param CommandSender $sender
     * @param string $currentAlias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)) return false;
        if(count($args) < 2){
            $sender->sendMessage(TF::RED . "Usage: /playsound <sound> <player> [x] [y] [z] [volume] [pitch]");
            return false;
        }
        $server = Server::getInstance();
        $player = $server->getPlayer($args[1]);
        if(!$player instanceof Player){
            $sender->sendMessage(TF::RED . "That player isn't online");
            return false;
        }
        $sound = $args[0] ?? "";
        $pk = new PlaySoundPacket();
        $pk->soundName = $sound;
        $pk->x = $args[2] ?? $player->getX();
        $pk->y = $args[3] ?? $player->getY();
        $pk->z = $args[4] ?? $player->getZ();
        $pk->volume = $args[5] ?? 500;
        $pk->pitch = $args[6] ?? 1;
        $server->broadcastPacket($player->getLevel()->getPlayers(), $pk);
        $sender->sendMessage(TF::GREEN . "Playing " . TF::YELLOW . $sound . TF::GREEN . " to " . TF::AQUA . $player->getName());
        return true;
    }
}