<?php

declare(strict_types=1);

// Andrew Gold - Spooky Scary Skeletons

/**
 * Spooky, scary skeletons
 * Send shivers down your spine
 * Shrieking skulls will shock your soul
 * Seal your doom tonight
 * Spooky, scary skeletons
 * Speak with such a screech
 * You'll shake and shudder in surprise
 * When you hear these zombies shriek
 * We're sorry skeletons, you're so misunderstood
 * You only want to socialize, but I don't think we should
 */

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\item\Item;

/**
 * Class SkeletonHorse
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class SkeletonHorse extends Horse {
    
    public const NETWORK_ID = self::SKELETON_HORSE;
    
    public function getName(): string{
        return "Skeleton Horse";
    }
    
    public function initEntity(): void{
        $this->setMaxHealth(30);
        parent::initEntity();
    }
    
    public function getDrops(): array{
        return $drops = [Item::get(Item::BONE, 0, mt_rand(0, 2)),];
    }
}
