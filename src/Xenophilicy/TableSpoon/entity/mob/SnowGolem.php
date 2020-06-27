<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\block\Block;
use pocketmine\block\SnowLayer;
use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\Utils;
use Xenophilicy\TableSpoon\utils\BiomeUtils;

/**
 * Class SnowGolem
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class SnowGolem extends Monster {
    
    public const NETWORK_ID = self::SNOW_GOLEM;
    public const TAG_PUMPKIN = "Pumpkin";
    public $width = 0.7;
    public $height = 1.9;
    
    public function getName(): string{
        return "Snow Golem";
    }
    
    public function initEntity(): void{
        if(!$this->namedtag->hasTag(self::TAG_PUMPKIN, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_PUMPKIN, 1);
        }
        
        $this->setMaxHealth(4);
        $this->setHealth(4);
        
        parent::initEntity();
    }
    
    public function onInteract(Player $player, Item $item, int $slot, Vector3 $clickPos): bool{
        return true;
    }
    
    public function isWearingPumpkin(): bool{
        return boolval($this->namedtag->getByte(self::TAG_PUMPKIN, 1));
    }
    
    public function setWearingPumpkin(bool $wearing): void{
        $this->namedtag->setByte(self::TAG_PUMPKIN, intval($wearing));
    }
    
    public function onUpdate(int $currentTick): bool{
        if($this->isFlaggedForDespawn() || !$this->isAlive()){
            return false;
        }
        $parent = parent::onUpdate($currentTick);
        if(TableSpoon::$settings["entities"]["golem"]["snow"]["snow-generation"]){
            if(Utils::canSeeSky($this->getLevel(), $this)){
                $lvl = $this->getLevel();
                for($x = -0.5; $x <= 0.5; $x += 0.5){
                    for($z = -0.5; $z <= 0.5; $z += 0.5){
                        $v3 = new Vector3(intval($this->getFloorX() + $x), intval($this->y), intval($this->getFloorZ() + $z));
                        if($lvl->getBlock($v3)->getId() == Block::AIR){
                            $lvl->setBlock($v3, new SnowLayer());
                        }
                    }
                }
            }
        }
        if(TableSpoon::$settings["entities"]["golem"]["snow"]["melting"]){
            $rainDamage = false;
            if(TableSpoon::$settings["waether"]["enabled"]){
                $weather = TableSpoon::$weatherData[$this->getLevel()->getId()];
                if($weather->isRainy() || $weather->isRainyThunder()){
                    if(Utils::canSeeSky($this->getLevel(), $this)){
                        $rainDamage = true;
                    }
                }
            }
            if(BiomeUtils::getTemperature(intval($this->x), intval($this->y), intval($this->z), $this->getLevel()) > 1.0 || $rainDamage){
                $this->attack(new EntityDamageEvent($this, EntityDamageEvent::CAUSE_FIRE, 0.5));
            }
        }
        return $parent;
    }
    
    public function getDrops(): array{
        return [Item::get(Item::SNOWBALL, 0, mt_rand(0, 15))];
    }
}
