<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;


use pocketmine\inventory\ContainerInventory;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use Xenophilicy\TableSpoon\tile\BrewingStand;

/**
 * Class BrewingInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class BrewingInventory extends ContainerInventory {
    public const SLOT_INGREDIENT = 0;
    public const SLOT_LEFT = 1;
    public const SLOT_MIDDLE = 2;
    public const SLOT_RIGHT = 3;
    public const SLOT_FUEL = 4;
    /** @var BrewingStand */
    protected $holder;
    
    /**
     * BrewingInventory constructor.
     * @param BrewingStand $holder
     * @param array $items
     * @param int|null $size
     * @param string|null $title
     */
    public function __construct(BrewingStand $holder, array $items = [], int $size = null, string $title = null){
        parent::__construct($holder, $items, $size, $title);
    }
    
    public function getDefaultSize(): int{
        return 5;
    }
    
    public function getName(): string{
        return "Brewing";
    }
    
    public function getNetworkType(): int{
        return WindowTypes::BREWING_STAND;
    }
    
    public function onSlotChange(int $index, Item $before, bool $send): void{
        $this->holder->scheduleUpdate();
        parent::onSlotChange($index, $before, $send);
    }
    
    public function getIngredient(): Item{
        return $this->getItem(self::SLOT_INGREDIENT);
    }
    
    public function setIngredient(Item $item): void{
        $this->setItem(self::SLOT_INGREDIENT, $item, true);
    }
    
    /**
     * @return Item[]
     */
    public function getPotions(): array{
        $return = [];
        for($i = 1; $i <= 3; $i++){
            $return[] = $this->getItem($i);
        }
        return $return;
    }
    
    public function onClose(Player $who): void{
        parent::onClose($who);
        $this->holder->saveNBT();
    }
    
    public function onOpen(Player $who): void{
        parent::onOpen($who);
        $this->holder->loadBottles();
    }
    
    public function getFuel(): Item{
        return $this->getItem(self::SLOT_FUEL);
    }
    
    public function setFuel(Item $fuel): void{
        $this->setItem(self::SLOT_FUEL, $fuel);
    }
}
