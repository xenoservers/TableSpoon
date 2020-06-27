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
 * Class Skeleton
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Skeleton extends Undead {
    
    public const NETWORK_ID = self::SKELETON;
    
    public $height = 1.99;
    public $width = 0.6;
    
    public function getName(): string{
        return "Skeleton";
    }
    
    public function getDrops(): array{
        return [Item::get(Item::ARROW, 0, mt_rand(0, 2)), Item::get(Item::BONE, 0, mt_rand(0, 2)),];
    }
}