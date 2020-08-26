<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, Solid};
use pocketmine\item\Item;

/**
 * Class EndPortal
 * @package Xenophilicy\TableSpoon\block
 */
class EndPortal extends Solid {
    
    /** @var int $id */
    protected $id = Block::END_PORTAL;
    
    /**
     * EndPortal constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        $this->meta = $meta;
    }
    
    /**
     * @return int
     */
    public function getLightLevel(): int{
        return 1;
    }
    
    /**
     * @return string
     */
    public function getName(): string{
        return "End Portal";
    }
    
    /**
     * @return float
     */
    public function getHardness(): float{
        return -1;
    }
    
    /**
     * @return float
     */
    public function getBlastResistance(): float{
        return 18000000;
    }
    
    /**
     * @param Item $item
     * @return bool
     */
    public function isBreakable(Item $item): bool{
        return false;
    }
    
    /**
     * @return bool
     */
    public function canPassThrough(): bool{
        return true;
    }
    
    /**
     * @return bool
     */
    public function hasEntityCollision(): bool{
        return true;
    }
}