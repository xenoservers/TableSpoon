<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\particle;

use pocketmine\level\particle\GenericParticle;
use pocketmine\math\Vector3;

/**
 * Class MobSpellParticle
 * @package Xenophilicy\TableSpoon\level\particle
 */
class MobSpellParticle extends GenericParticle {
    /**
     * MobSpellParticle constructor.
     * @param Vector3 $pos
     * @param int $r
     * @param int $g
     * @param int $b
     * @param int $a
     */
    public function __construct(Vector3 $pos, $r = 0, $g = 0, $b = 0, $a = 255){
        parent::__construct($pos, Particle::TYPE_MOB_SPELL, (($a & 0xff) << 24) | (($r & 0xff) << 16) | (($g & 0xff) << 8) | ($b & 0xff));
    }
}