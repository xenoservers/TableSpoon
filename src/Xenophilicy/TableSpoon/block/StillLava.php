<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

/**
 * Class StillLava
 * @package Xenophilicy\TableSpoon\block
 */
class StillLava extends Lava {
    
    /** @var int $id */
    protected $id = self::STILL_LAVA;
    
    /**
     * @return string
     */
    public function getName(): string{
        return "Still Lava";
    }
}
