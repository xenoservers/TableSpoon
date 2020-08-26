<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\Player;
use pocketmine\tile\Container;
use Xenophilicy\TableSpoon\tile\ShulkerBox as TileShulkerBox;
use Xenophilicy\TableSpoon\tile\Tile;


/**
 * Class ShulkerBox
 * @package Xenophilicy\TableSpoon\item
 */
class ShulkerBox extends Item {
    
    const WHITE = 0;
    const ORANGE = 1;
    const MAGENTA = 2;
    const LIGHT_BLUE = 3;
    const YELLOW = 4;
    const LIME = 5;
    const PINK = 6;
    const GRAY = 7;
    const LIGHT_GRAY = 8;
    const CYAN = 9;
    const PURPLE = 10;
    const BLUE = 11;
    const BROWN = 12;
    const GREEN = 13;
    const RED = 14;
    const BLACK = 15;
    
    /**
     * @param int $meta
     * @param string|null $name
     * @param NamedTag|null $inventory
     */
    public function __construct(int $meta = 0, ?string $name = null, ?NamedTag $inventory = null){
        if($name === null){
            $name = $this->getColorName($meta) . " Shulker Box";
        }
        parent::__construct(self::SHULKER_BOX, $meta, $name);
        if($inventory !== null){
            $this->getNamedTag()->setTag($inventory);
        }
    }
    
    /**
     * @param int $meta
     * @return string
     */
    private function getColorName(int $meta): string{
        switch($meta){
            case self::ORANGE:
                return "Orange";
            case self::MAGENTA:
                return "Magenta";
            case self::LIGHT_BLUE:
                return "Light Blue";
            case self::YELLOW:
                return "Yellow";
            case self::LIME:
                return "Lime";
            case self::PINK:
                return "Pink";
            case self::GRAY:
                return "Gray";
            case self::LIGHT_GRAY:
                return "Light Gray";
            case self::CYAN:
                return "Cyan";
            case self::PURPLE:
                return "Purple";
            case self::BLUE:
                return "Blue";
            case self::BROWN:
                return "Brown";
            case self::GREEN:
                return "Green";
            case self::RED:
                return "Red";
            case self::BLACK:
                return "Black";
            default:
                return "White";
        }
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        $block = BlockFactory::get($this->id, $this->meta, $blockReplace);
        $blockReplace->level->setBlock($blockReplace, $block, true, true);
        $tileNBT = TileShulkerBox::createNBT($blockReplace, $face, $this, $player);
        $containerTag = $this->getNamedTag()->getTag(Container::TAG_ITEMS);
        if($containerTag !== null){
            $tileNBT->setTag($containerTag);
        }
        $t = TileShulkerBox::createTile(Tile::SHULKER_BOX, $player->level, $tileNBT);
        $player->getServer()->saveOfflinePlayerData("MyShulkerBox", $t->getCleanedNBT());
        if($player->getGamemode() !== Player::CREATIVE){
            $this->pop();
        }
        return true;
    }
}