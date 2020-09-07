<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\task;

use pocketmine\scheduler\Task;
use Xenophilicy\TableSpoon\player\PlayerSessionManager;

class SessionManagerTask extends Task {
    
    public function onRun(int $currentTick){
        foreach(PlayerSessionManager::$ticking as $playerID){
            PlayerSessionManager::$players[$playerID]->tick();
        }
    }
    
}