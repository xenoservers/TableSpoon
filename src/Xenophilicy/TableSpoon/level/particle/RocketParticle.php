<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\particle;

use pocketmine\level\particle\GenericParticle;
use pocketmine\math\Vector3;

/**
 * Class RocketParticle
 * @package Xenophilicy\TableSpoon\level\particle
 */
class RocketParticle extends GenericParticle {
    /**
     * RocketParticle constructor.
     * @param Vector3 $pos
     */
    public function __construct(Vector3 $pos){
        parent::__construct($pos, Particle::TYPE_FIREWORK_WHITE, 0);
    }
}