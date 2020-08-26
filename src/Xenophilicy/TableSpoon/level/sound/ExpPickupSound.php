<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\sound;

use pocketmine\level\sound\GenericSound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

/**
 * Class ExpPickupSound
 * @package Xenophilicy\TableSpoon\level\sound
 */
class ExpPickupSound extends GenericSound {
    /**
     * ExpPickupSound constructor.
     * @param Vector3 $pos
     * @param int $pitch
     */
    public function __construct(Vector3 $pos, $pitch = 0){
        parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ORB, $pitch);
    }
}