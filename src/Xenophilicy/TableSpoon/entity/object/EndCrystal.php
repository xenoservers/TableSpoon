<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\object;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Explosion;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class EndCrystal
 * @package Xenophilicy\TableSpoon\entity\object
 */
class EndCrystal extends Entity {
    
    public const TAG_SHOW_BOTTOM = "ShowBottom";
    
    public const NETWORK_ID = self::ENDER_CRYSTAL;
    
    public $height = 0.98;
    public $width = 0.98;
    
    public function initEntity(): void{
        if(!$this->namedtag->hasTag(self::TAG_SHOW_BOTTOM, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_SHOW_BOTTOM, 0);
        }
        parent::initEntity();
    }
    
    public function isShowingBottom(): bool{
        return boolval($this->namedtag->getByte(self::TAG_SHOW_BOTTOM));
    }
    
    /**
     * @param bool $value
     */
    public function setShowingBottom(bool $value){
        $this->namedtag->setByte(self::TAG_SHOW_BOTTOM, intval($value));
    }
    
    /**
     * @param Vector3 $pos
     */
    public function setBeamTarget(Vector3 $pos){
        $this->namedtag->setTag(new ListTag("BeamTarget", [new DoubleTag("", $pos->getX()), new DoubleTag("", $pos->getY()), new DoubleTag("", $pos->getZ())]));
    }
    
    public function attack(EntityDamageEvent $source): void{
        if(TableSpoon::$settings["entities"]["end-crystal"]["explosions"]){
            if($this->isFlaggedForDespawn()){
                return;
            }
            $this->flagForDespawn();
            $explode = new Explosion($this, TableSpoon::$settings["entities"]["end-crystal"]["power"], $this);
            $explode->explodeA();
            $explode->explodeB();
        }
    }
}
