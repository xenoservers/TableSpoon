<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\player;

use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\task\SessionManagerTask;

/**
 * Class SessionManger
 * @package Xenophilicy\TableSpoon\player
 */
final class PlayerSessionManager {
    
    /** @var PlayerSession[] */
    public static $players = [];
    
    /** @var int[] */
    public static $ticking = [];
    
    public static function init(): void{
        TableSpoon::getInstance()->getScheduler()->scheduleRepeatingTask(new SessionManagerTask(), 1);
    }
    
    public static function create(Player $player): void{
        self::$players[$player->getId()] = new PlayerSession($player);
    }
    
    public static function destroy(Player $player): void{
        self::stopTicking($player);
        unset(self::$players[$player->getId()]);
    }
    
    public static function stopTicking(Player $player): void{
        unset(self::$ticking[$player->getId()]);
    }
    
    public static function get(Player $player): ?PlayerSession{
        return self::$players[$player->getId()] ?? null;
    }
    
    public static function scheduleTicking(Player $player): void{
        $playerID = $player->getId();
        self::$ticking[$playerID] = $playerID;
    }
}