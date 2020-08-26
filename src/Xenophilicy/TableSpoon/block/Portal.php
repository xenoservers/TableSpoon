<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Air, Block, BlockToolType, Transparent};
use pocketmine\item\{Item};
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class Portal
 * @package Xenophilicy\TableSpoon\block
 */
class Portal extends Transparent {
    
    /** @var int $id */
    protected $id = Block::PORTAL;
    
    /**
     * Portal constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        $this->meta = $meta;
    }
    
    /**
     * @return string
     */
    public function getName(): string{
        return "Portal";
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
    public function getResistance(): float{
        return 0;
    }
    
    /**
     * @return int
     */
    public function getToolType(): int{
        return BlockToolType::TYPE_PICKAXE;
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
    
    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onBreak(Item $item, Player $player = null): bool{
        if($this->getSide(Vector3::SIDE_WEST) instanceof Portal or $this->getSide(Vector3::SIDE_EAST) instanceof Portal){//x方向
            for($x = $this->x; $this->getLevel()->getBlockIdAt($x, $this->y, $this->z) == Block::PORTAL; $x++){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
            }
            for($x = $this->x - 1; $this->getLevel()->getBlockIdAt($x, $this->y, $this->z) == Block::PORTAL; $x--){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
            }
        }else{
            for($z = $this->z; $this->getLevel()->getBlockIdAt($this->x, $this->y, $z) == Block::PORTAL; $z++){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
            }
            for($z = $this->z - 1; $this->getLevel()->getBlockIdAt($this->x, $this->y, $z) == Block::PORTAL; $z--){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
            }
        }
        return true;
    }
    
    /**
     * @param Item $item
     * @param Block $block
     * @param Block $target
     * @param int $face
     * @param Vector3 $facePos
     * @param Player|null $player
     * @return bool
     */
    public function place(Item $item, Block $block, Block $target, int $face, Vector3 $facePos, Player $player = null): bool{
        if($player instanceof Player){
            $this->meta = $player->getDirection() & 0x01;
        }
        $this->getLevel()->setBlock($block, $this, true, true);
        return true;
    }
}
