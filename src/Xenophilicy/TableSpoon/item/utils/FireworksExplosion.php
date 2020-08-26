<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item\utils;


/**
 * Class FireworksExplosion
 * @package Xenophilicy\TableSpoon\item\utils
 */
class FireworksExplosion {
    
    /** @var int */
    public const
      TYPE_SMALL_BALL = 0, TYPE_LARGE_BALL = 1, TYPE_STAR_SHAPED = 2, TYPE_CREEPER_SHAPED = 3, TYPE_BURST = 4;
    
    /** @var int */
    public const
      COLOR_BLACK = 0, COLOR_RED = 1, COLOR_GREEN = 2, COLOR_BROWN = 3, COLOR_BLUE = 4, COLOR_PURPLE = 5, COLOR_CYAN = 6, COLOR_LIGHT_GRAY = 7, COLOR_GRAY = 8, COLOR_PINK = 9, COLOR_LIME = 10, COLOR_YELLOW = 11, COLOR_LIGHT_BLUE = 12, COLOR_MAGENTA = 13, COLOR_ORANGE = 14, COLOR_WHITE = 15;
    
    /** @var int[] */
    public $fireworkColor = [self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK];
    /** @var int[] */
    public $fireworkFade = [self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK];
    /** @var bool */
    public $fireworkFlicker = false;
    /** @var bool */
    public $fireworkTrail = false;
    /** @var int */
    public $fireworkType = self::TYPE_SMALL_BALL;
}
