<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\player;

use Xenophilicy\TableSpoon\block\multiblock\PortalMultiBlock;

/**
 * Class PlayerPortalInfo
 * @package Xenophilicy\TableSpoon\player
 */
final class PlayerPortalInfo {
    
    /** @var PortalMultiBlock */
    private $block;
    
    /** @var int */
    private $duration = 0;
    
    /** @var int */
    private $max_duration;
    
    /**
     * PlayerPortalInfo constructor.
     * @param PortalMultiBlock $block
     * @param int $max_duration
     */
    public function __construct(PortalMultiBlock $block, int $max_duration){
        $this->block = $block;
        $this->max_duration = $max_duration;
    }
    
    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }
    
    public function tick(): bool{
        if($this->duration === $this->max_duration){
            $this->duration = 0;
            return true;
        }
        
        ++$this->duration;
        return false;
    }
}