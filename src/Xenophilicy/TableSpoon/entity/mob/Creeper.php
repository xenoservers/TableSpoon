<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class Creeper
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Creeper extends Monster {
    
    public const NETWORK_ID = self::CREEPER;
    public const TAG_POWERED = "powered";
    public const TAG_IGNITED = "ignited";
    public const TAG_FUSE = "Fuse";
    public const TAG_EXPLOSION_RADIUS = "ExplosionRadius";
    public $height = 1.7;
    public $width = 0.6;
    
    public function initEntity(): void{
        parent::initEntity();
        
        if(!$this->namedtag->hasTag(self::TAG_POWERED, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_POWERED, 0);
        }
        
        if($this->namedtag->hasTag(self::TAG_EXPLOSION_RADIUS, ShortTag::class)){ // oopsie whoopsie we made a fucky wucky [73f710b]
            $this->namedtag->removeTag(self::TAG_EXPLOSION_RADIUS);
        }
        if(!$this->namedtag->hasTag(self::TAG_EXPLOSION_RADIUS, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_EXPLOSION_RADIUS, 3);
        }
        
        if(!$this->namedtag->hasTag(self::TAG_FUSE, ShortTag::class)){
            $this->namedtag->setShort(self::TAG_FUSE, 30);
        }
        
        if(!$this->namedtag->hasTag(self::TAG_IGNITED, ByteTag::class)){
            $this->namedtag->setByte(self::TAG_IGNITED, 0);
        }
    }
    
    public function entityBaseTick(int $tickDiff = 1): bool{
        $parent = parent::entityBaseTick($tickDiff);
        if($this->isIgnited()){
            $fuse = $this->getFuse() - $tickDiff;
            $this->setFuse($fuse);
            if($fuse <= 0){
                $this->explode();
            }
        }
        return $parent;
    }
    
    public function isIgnited(): bool{
        return ($this->getGenericFlag(self::DATA_FLAG_IGNITED) || boolval($this->namedtag->getByte(self::TAG_IGNITED, 0)));
    }
    
    public function getFuse(): int{
        return $this->namedtag->getShort(self::TAG_FUSE, 30);
    }
    
    public function setFuse(int $fuse): void{
        $this->namedtag->setShort(self::TAG_FUSE, $fuse);
    }
    
    public function explode(){
        $this->kill();
        if(TableSpoon::$settings["entites"]["creeper"]["explosions"]){
            $pow = $this->getExplosionRadius();
            if($this->isPowered()){
                $pow *= 2;
            }
            $explosion = new Explosion($this, $pow, $this);
            $explosion->explodeA();
            $explosion->explodeB();
        }
    }
    
    public function getExplosionRadius(): int{
        return $this->namedtag->getByte(self::TAG_EXPLOSION_RADIUS, 3);
    }
    
    public function isPowered(): bool{
        return ($this->getGenericFlag(self::DATA_FLAG_POWERED) || boolval($this->namedtag->getByte(self::TAG_POWERED, 0)));
    }
    
    public function getName(): string{
        return "Creeper";
    }
    
    public function getDrops(): array{
        if(mt_rand(1, 10) < 3){
            return [Item::get(Item::GUNPOWDER, 0, 1)];
        }
        return [];
    }
    
    public function setPowered(bool $powered): void{
        $this->namedtag->setByte(self::TAG_POWERED, intval($powered));
        $this->setGenericFlag(self::DATA_FLAG_POWERED, $powered);
    }
    
    public function setExplosionRadius(int $explosionRadius): void{
        $this->namedtag->setByte(self::TAG_EXPLOSION_RADIUS, $explosionRadius);
    }
    
    public function onInteract(Player $player, Item $item, int $slot, Vector3 $clickPos): bool{
        if(TableSpoon::$settings["entites"]["creeper"]["ignite"] && $item->getId() == Item::FLINT_AND_STEEL && !$this->isIgnited()){
            $this->setIgnited(true);
        }
        return true;
    }
    
    public function setIgnited(bool $ignited): void{
        $this->namedtag->setByte(self::TAG_IGNITED, intval($ignited));
        $this->setGenericFlag(self::DATA_FLAG_IGNITED, $ignited);
    }
}
