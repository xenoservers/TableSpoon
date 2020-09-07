<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\entity\Entity;
use pocketmine\item\Tool;
use pocketmine\Player;
use function min;

/**
 * Class Trident
 * @package Xenophilicy\TableSpoon\item
 */
class Trident extends Tool {
    
    public const TAG_TRIDENT = "Trident";
    
    /**
     * Trident constructor.
     * @param int $meta
     * @param int $count
     */
    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::TRIDENT, $meta, "Trident");
    }
    
    public function getMaxDurability(): int{
        return 251;
    }
    
    public function onReleaseUsing(Player $player): bool{
        $diff = $player->getItemUseDuration();
        $p = $diff / 10;
        $force = min((($p ** 2) + $p * 2) / 3, 1) * 2;
        if($force < 0.15 or $diff < 2){
            return false;
        }
        $nbt = Entity::createBaseNBT($player->add(0, $player->getEyeHeight(), 0), $player->getDirectionVector()->multiply($force), ($player->yaw > 180 ? 360 : 0) - $player->yaw, -$player->pitch);
        if($player->isSurvival()){
            $this->applyDamage(1);
        }
        $nbt->setTag($this->nbtSerialize(-1, self::TAG_TRIDENT));
        $entity = Entity::createEntity(Entity::TRIDENT, $player->getLevel(), $nbt, $player, $this);
        $entity->spawnToAll();
        if($player->isSurvival()){
            $this->setCount(0);
        }
        return true;
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function onAttackEntity(Entity $victim): bool{
        return $this->applyDamage(1);
    }
    
    public function getAttackPoints(): int{
        return 8;
    }
}
