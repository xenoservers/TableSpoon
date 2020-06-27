<?php

/*
 * Credits to @thebigsmileXD (XenialDan)
 * Original Repository: https://github.com/thebigsmileXD/fireworks
 * Ported to TableSpoon as TableSpoon overrides the fireworks item (as Elytra Booster)
 * Licensed under the MIT License (January 1, 2018)
 * */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item\utils;


/**
 * Class FireworksData
 * @package Xenophilicy\TableSpoon\item\utils
 */
class FireworksData {
    /** @var int */
    public $flight = 1;
    /** @var FireworksExplosion[] */
    public $explosions = [];
}