<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\task;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\{ChangeDimensionPacket, PlayStatusPacket};
use pocketmine\Player;
use pocketmine\scheduler\Task;
use Xenophilicy\TableSpoon\{TableSpoon, Utils};

/**
 * Class DelayedCrossDimensionTeleportTask
 * @package Xenophilicy\TableSpoon\task
 */
class DelayedCrossDimensionTeleportTask extends Task{
    /** @var Player */
    protected $player;

    /** @var int */
    protected $dimension;

    /** @var Vector3 */
    protected $position;

    /** @var bool */
    protected $respawn;

    /**
     * DelayedCrossDimensionTeleportTask constructor.
     * @param Player $player
     * @param int $dimension
     * @param Vector3 $position
     * @param bool $respawn
     */
    public function __construct(Player $player, int $dimension, Vector3 $position, bool $respawn = false){
        $this->player = $player;
        $this->dimension = $dimension;
        $this->position = $position;
        $this->respawn = $respawn;
    }

    /**
     * @param int $currentTick
     * @return bool|void
     */
    public function onRun(int $currentTick){
        if(Utils::isDelayedTeleportCancellable($this->player, $this->dimension)){
            unset(TableSpoon::$onPortal[$this->player->getId()]);
            return false;
        }
        $pk = new ChangeDimensionPacket();
        $pk->dimension = $this->dimension;
        $pk->position = $this->position;
        $pk->respawn = $this->respawn;
        $this->player->dataPacket($pk);
        $this->player->sendPlayStatus(PlayStatusPacket::PLAYER_SPAWN);
        $this->player->teleport($this->position);
        unset(TableSpoon::$onPortal[$this->player->getId()]);
        return true;
    }
}
