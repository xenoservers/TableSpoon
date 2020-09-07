<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace Xenophilicy\TableSpoon\block\multiblock;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\Player;
use ReflectionProperty;
use Xenophilicy\TableSpoon\block\EndPortalFrame;

/**
 * Class EndPortalFrameMultiBlock
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
class EndPortalFrameMultiBlock implements MultiBlock {
    
    private const SIDES = [Vector3::SIDE_NORTH, Vector3::SIDE_EAST, Vector3::SIDE_SOUTH, Vector3::SIDE_WEST];
    
    /** @var ReflectionProperty */
    private $property_eye;
    
    public function __construct(){
        $this->property_eye = new ReflectionProperty(EndPortalFrame::class, "eye");
        $this->property_eye->setAccessible(true);
    }
    
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool{
        $eyed = $wrapping->getDamage() >= 4 && $wrapping->getDamage() <= 7;
        if(!$eyed){
            if($item->getId() === ItemIds::ENDER_EYE){
                $item->pop();
                $this->property_eye->setValue($wrapping, true);
                $wrapping->getLevel()->setBlock($wrapping, $wrapping, false);
                $this->tryCreatingPortal($wrapping);
                return true;
            }
        }elseif($item->getId() !== ItemIds::ENDER_EYE){
            $this->property_eye->setValue($wrapping, false);
            $level = $wrapping->getLevel();
            $level->setBlock($wrapping, $wrapping, false);
            $level->dropItem($wrapping->add(0.5, 0.75, 0.5), ItemFactory::get(ItemIds::ENDER_EYE));
            $this->tryDestroyingPortal($wrapping);
            return true;
        }
        return false;
    }
    
    public function tryCreatingPortal(Block $wrapping): void{
        for($i = 0; $i < 4; ++$i){
            for($j = -1; $j <= 1; ++$j){
                $center = $wrapping->getSide(self::SIDES[$i], 2)->getSide(self::SIDES[($i + 1) % 4], $j);
                if($this->isCompletedPortal($center)){
                    $this->createPortal($center);
                }
            }
        }
    }
    
    public function isCompletedPortal(Block $center): bool{
        for($i = 0; $i < 4; ++$i){
            for($j = -1; $j <= 1; ++$j){
                $block = $center->getSide(self::SIDES[$i], 2)->getSide(self::SIDES[($i + 1) % 4], $j);
                if(!($block instanceof EndPortalFrame) || !$this->property_eye->getValue($block)){
                    return false;
                }
            }
        }
        return true;
    }
    
    public function createPortal(Block $block): void{
        $level = $block->getLevel();
        for($i = -1; $i <= 1; ++$i){
            for($j = -1; $j <= 1; ++$j){
                $level->setBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() + $j), BlockFactory::get(Block::END_PORTAL), false);
            }
        }
    }
    
    public function tryDestroyingPortal(Block $block): void{
        for($i = 0; $i < 4; ++$i){
            for($j = -1; $j <= 1; ++$j){
                $center = $block->getSide(self::SIDES[$i], 2)->getSide(self::SIDES[($i + 1) % 4], $j);
                if(!$this->isCompletedPortal($center)){
                    $this->destroyPortal($center);
                }
            }
        }
    }
    
    public function destroyPortal(Block $block): void{
        $level = $block->getLevel();
        for($i = -1; $i <= 1; ++$i){
            for($j = -1; $j <= 1; ++$j){
                $blockPos = new Vector3($block->getX(), $block->getY(), $block->getZ() + $j);
                if($level->getBlock($blockPos)->getId() === Block::END_PORTAL) $level->setBlock($blockPos, new Air(), false);
            }
        }
    }
    
    public function update(Block $wrapping): bool{
        if($this->property_eye->getValue($wrapping)){
            $this->tryDestroyingPortal($wrapping);
        }
        return false;
    }
    
    public function onPlayerMoveInside(Player $player, Block $block): void{
    }
    
    public function onPlayerMoveOutside(Player $player, Block $block): void{
    }
}