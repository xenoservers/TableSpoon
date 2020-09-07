<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\projectile;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\projectile\Arrow as PMArrow;
use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\math\RayTraceResult;
use pocketmine\utils\Color;
use Xenophilicy\TableSpoon\level\particle\MobSpellParticle;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Arrow
 * @package Xenophilicy\TableSpoon\entity\projectile
 */
class Arrow extends PMArrow {
    /** @var int */
    protected $potionId;
    /** @var Color */
    protected $color;
    
    public function initEntity(): void{
        $this->potionId = $this->namedtag->getShort("Potion", 0);
        if($this->potionId >= 1 && $this->potionId <= 36){
            $this->color = Utils::getPotionColor($this->potionId);
        }
        
        parent::initEntity();
    }
    
    public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void{
        parent::onHitEntity($entityHit, $hitResult);
        
        if($this->potionId >= 1 && $this->potionId <= 36 && $entityHit instanceof Living){
            foreach(Potion::getPotionEffectsById($this->potionId) as $effect){
                $entityHit->addEffect($effect);
            }
        }
    }
    
    public function onUpdate(int $currentTick): bool{
        $hasUpdate = parent::onUpdate($currentTick);
        
        if($this->potionId >= 1 && $this->potionId <= 36){
            if(!$this->isOnGround() or ($this->isOnGround() and ($currentTick % 4) == 0)){
                if($this->getLevel() instanceof Level && $this->color instanceof Color){
                    $this->getLevel()->addParticle(new MobSpellParticle($this->asVector3(), $this->color->getR(), $this->color->getG(), $this->color->getB(), $this->color->getA()));
                }
            }
            $hasUpdate = true;
        }
        return $hasUpdate;
    }
}
