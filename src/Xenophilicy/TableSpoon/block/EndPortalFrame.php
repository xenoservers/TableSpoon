<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, EndPortalFrame as PMEndPortalFrame};
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class EndPortalFrame
 * @package Xenophilicy\TableSpoon\block
 */
class EndPortalFrame extends PMEndPortalFrame {
    
    public $eye = false;
    
    /**
     * EndPortalFrame constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct($meta);
    }
    
    // Code below is ported from ClearSky (Big Thanks to XenialDan)
    
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
        $faces = [0 => 3, 1 => 0, 2 => 1, 3 => 2];
        $this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
        $this->getLevel()->setBlock($block, $this, true, true);
        return true;
    }
    
    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool{
        if(($this->getDamage() & 0x04) === 0 && $player instanceof Player && $item->getId() === Item::ENDER_EYE){
            $this->setDamage($this->getDamage() + 4);
            $this->getLevel()->setBlock($this, $this, true, true);
            /*$corners = $this->isValidPortal();
            if(is_array($corners)){
              $this->createPortal($corners);
            }*/
            return true;
        }
        return false;
    }
    
    //    /**
    //     * @return array
    //     */
    //    public function isValidPortal(): array{
    //        // TODO: Portal Checks
    //        return [new Vector3(0, 0, 0), // corner 1
    //            new Vector3(0, 0, 0), // corner 2
    //            new Vector3(0, 0, 0), // corner 3
    //            new Vector3(0, 0, 0), // corner 4
    //        ];
    //    }
    //
    //    /**
    //     * @param array|null $corners
    //     * @return bool
    //     */
    //    public function createPortal(array $corners = null): bool{
    //        // Accepted Format:
    //        /*
    //         * Array:
    //         *  - CORNER X ONE
    //         *  - CORNER X TWO
    //         *  - CORNER Z ONE
    //         *  - CORNER Z TWO
    //         *  - BLOCK Y
    //         */
    //        if($corners === null){
    //            return false;
    //        }
    //        $x1 = min($corners[0][0], $corners[1][0]);
    //        $x2 = max($corners[0][0], $corners[1][0]);
    //        $z1 = min($corners[0][1], $corners[1][1]);
    //        $z2 = max($corners[0][1], $corners[1][1]);
    //        $y = $corners[2];
    //        for($curX = $x1; $curX <= $x2; $curX++){
    //            for($curZ = $z1; $curZ <= $z2; $curZ++){
    //                $pos = new Vector3($curX, $y, $curZ);
    //                $this->getLevel()->setBlock($pos, Block::get(Block::END_PORTAL), false, false);
    //            }
    //        }
    //        return true;
    //    }
}