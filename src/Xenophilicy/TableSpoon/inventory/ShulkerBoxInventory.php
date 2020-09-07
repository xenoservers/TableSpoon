<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\inventory;


use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use Xenophilicy\TableSpoon\tile\ShulkerBox;

/**
 * Class ShulkerBoxInventory
 * @package Xenophilicy\TableSpoon\inventory
 */
class ShulkerBoxInventory extends ContainerInventory {
    
    /** @var ShulkerBox */
    protected $holder;
    
    /**
     * ShulkerBoxInventory constructor.
     * @param ShulkerBox $tile
     */
    public function __construct(ShulkerBox $tile){
        parent::__construct($tile);
    }
    
    public function getName(): string{
        return "Shulker Box";
    }
    
    public function getDefaultSize(): int{
        return 27;
    }
    
    public function getNetworkType(): int{
        return WindowTypes::CONTAINER;
    }
    
    public function onOpen(Player $who): void{
        parent::onOpen($who);
        if(count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level){
            $this->broadcastBlockEventPacket($this->getHolder(), true);
            $level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKERBOX_OPEN);
        }
    }
    
    /**
     * @return Vector3|ShulkerBox
     */
    public function getHolder(){
        return $this->holder;
    }
    
    /**
     * @param Vector3 $vector
     * @param bool $isOpen
     */
    protected function broadcastBlockEventPacket(Vector3 $vector, bool $isOpen){
        $pk = new BlockEventPacket();
        $pk->x = (int)$vector->x;
        $pk->y = (int)$vector->y;
        $pk->z = (int)$vector->z;
        $pk->eventType = 1;
        $pk->eventData = $isOpen ? 1 : 0;
        $this->getHolder()->getLevel()->addChunkPacket($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4, $pk);
    }
    
    public function onClose(Player $who): void{
        if(count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level){
            $this->broadcastBlockEventPacket($this->getHolder(), false);
            $level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKERBOX_CLOSED);
        }
        parent::onClose($who);
    }
}