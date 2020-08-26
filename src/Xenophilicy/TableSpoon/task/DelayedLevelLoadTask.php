<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\task;

use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use Xenophilicy\TableSpoon\level\LevelManager;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class DelayedLevelLoadTask
 * @package Xenophilicy\TableSpoon\task
 */
class DelayedLevelLoadTask extends Task {
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        LevelManager::init();
        if(TableSpoon::$overworldLevel instanceof Level) return;
        TableSpoon::getInstance()->getScheduler()->scheduleTask(new $this());
    }
}
