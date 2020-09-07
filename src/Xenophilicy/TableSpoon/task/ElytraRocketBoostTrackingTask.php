<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Xenophilicy\TableSpoon\level\particle\RocketParticle;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class ElytraRocketBoostTrackingTask
 * @package Xenophilicy\TableSpoon\task
 */
class ElytraRocketBoostTrackingTask extends Task {
    /** @var Player */
    protected $player;
    
    /** @var int */
    protected $count;
    
    /** @var int */
    private $internalCount = 1;
    
    /**
     * ElytraRocketBoostTrackingTask constructor.
     * @param Player $player
     * @param int $count
     */
    public function __construct(Player $player, int $count){
        $this->player = $player;
        $this->count = $count;
    }
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        if($this->internalCount <= $this->count){
            $this->player->getLevel()->addParticle(new RocketParticle($this->player->asVector3()->add($this->player->width / 2 + mt_rand(-100, 100) / 500, $this->player->height / 2 + mt_rand(-100, 100) / 500, $this->player->width / 2 + mt_rand(-100, 100) / 500)));
            $this->internalCount++;
        }else{
            TableSpoon::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}