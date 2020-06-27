<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\nbt\tag\{ByteTag};

/**
 * Class Bat
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Bat extends Animal {
    
    public const TAG_IS_RESTING = "isResting";
    
    public const NETWORK_ID = self::BAT;
    
    public $width = 0.5;
    public $height = 0.9;
    protected $age = 0;
    
    public function initEntity(): void{
        if(!$this->namedtag->hasTag(self::TAG_IS_RESTING, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_IS_RESTING, 0);
        }
        $this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_RESTING, boolval($this->namedtag->getByte(self::TAG_IS_RESTING)));
        $this->setMaxHealth(6);
        
        parent::initEntity();
    }
    
    public function isResting(): bool{
        return boolval($this->namedtag->getByte(self::TAG_IS_RESTING));
    }
    
    public function getName(): string{
        return "Bat";
    }
    
    /**
     * @param bool $resting
     */
    public function setResting(bool $resting){
        $this->namedtag->setByte(self::TAG_IS_RESTING, intval($resting));
    }
    
    public function onUpdate(int $currentTick): bool{
        if($this->age > 1200){
            $this->kill();
        }
        return parent::onUpdate($currentTick);
    }
}
