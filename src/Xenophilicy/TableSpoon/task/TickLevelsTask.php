<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\task;

use pocketmine\scheduler\Task;
use Xenophilicy\TableSpoon\level\LevelManager;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class TickLevelsTask
 * @package Xenophilicy\TableSpoon\task
 */
class TickLevelsTask extends Task {
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        if(!LevelManager::$loaded){
            return;
        }
        foreach(TableSpoon::$weatherData as $weather){
            $weather->calcWeather($currentTick);
        }
    }
}
