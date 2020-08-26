<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Air;
use pocketmine\block\SnowLayer as PMSnowLayer;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\Utils;
use Xenophilicy\TableSpoon\utils\BiomeUtils;

/**
 * Class SnowLayer
 * @package Xenophilicy\TableSpoon\block
 */
class SnowLayer extends PMSnowLayer {
    public function onRandomTick(): void{
        if(TableSpoon::$settings["blocks"]["snow-melts"]){
            $destroy = false;
            if(TableSpoon::$settings["weather"]["enabled"]){
                $weather = TableSpoon::$weatherData[$this->getLevel()->getId()];
                if($weather->isRainy() || $weather->isRainyThunder()){
                    if(Utils::canSeeSky($this->getLevel(), $this)){
                        $destroy = true;
                    }
                }
            }
            if(BiomeUtils::getTemperature($this->x, $this->y, $this->z, $this->getLevel()) > 1.0){
                $destroy = true;
            }
            if($destroy){
                $this->getLevel()->setBlock($this, new Air());
            }
        }
    }
    
    public function ticksRandomly(): bool{
        return true;
    }
}