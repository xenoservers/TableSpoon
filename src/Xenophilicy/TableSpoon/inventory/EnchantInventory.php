<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;

use pocketmine\inventory\EnchantInventory as PMEnchantInventory;
use pocketmine\Player;
use pocketmine\utils\Random;
use Xenophilicy\TableSpoon\block\EnchantingTable;

/**
 * Class EnchantInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class EnchantInventory extends PMEnchantInventory {
    // TODO: Add Enchantment verification (if possible)
    public $random = null;
    
    public $bookshelfAmount = 0;
    
    public $levels = null;
    public $entries = null;
    
    public function onOpen(Player $who): void{
        parent::onOpen($who);
        $holder = $this->getHolder();
        if($holder instanceof EnchantingTable){
            if($this->levels == null){
                $this->levels = [];
                $this->bookshelfAmount = $holder->countBookshelf();
                if($this->bookshelfAmount < 0){
                    $this->bookshelfAmount = 0;
                }
                if($this->bookshelfAmount > 15){
                    $this->bookshelfAmount = 15;
                }
                $random = new Random();
                $base = (double)$random->nextRange(1, 8) + ($this->bookshelfAmount / 2) + (double)$random->nextRange(0, $this->bookshelfAmount);
                $this->levels[0] = (int)max($base / 3, 1);
                $this->levels[1] = (int)(($base * 2) / 3 + 1);
                $this->levels[2] = (int)max($base, $this->bookshelfAmount * 2);
            }
        }
    }
    
    public function onClose(Player $who): void{
        $this->dropContents($this->holder->getLevel(), $this->holder->add(0.5, 0.5, 0.5));
    }
}