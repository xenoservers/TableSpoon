<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, Ice};

/**
 * Class FrostedIce
 * @package Xenophilicy\TableSpoon\block
 */
class FrostedIce extends Ice {
    
    /** @var int $id */
    protected $id = self::FROSTED_ICE;
    
    /**
     * @return string
     */
    public function getName(): string{
        return "Frosted Ice";
    }
    
    public function onRandomTick(): void{
        $this->meta++;
        $this->getLevel()->setBlock($this->asVector3(), $this, false, false);
        if($this->meta > 3){
            $this->getLevel()->setBlock($this->asVector3(), Block::get(Block::WATER), false, true);
        }
    }
}