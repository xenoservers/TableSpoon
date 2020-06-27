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

/**
 * Class Stray
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Stray extends Skeleton {
    
    public const NETWORK_ID = self::STRAY;
    
    public function getName(): string{
        return "Stray";
    }
}