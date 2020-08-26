<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\{Block, BlockFactory, Fire as PMFire};
use pocketmine\math\Vector3;
use Xenophilicy\TableSpoon\{TableSpoon, Utils};

/**
 * Class Fire
 * @package Xenophilicy\TableSpoon\block
 */
class Fire extends PMFire {
    
    public function onScheduledUpdate(): void{
        if($this->meta >= 15){
            $this->level->setBlock($this, BlockFactory::get(Block::AIR));
        }else{
            $this->meta += mt_rand(1, 4);
            $this->level->setBlock($this, $this);
        }
    }
    
    public function onRandomTick(): void{
        if(isset(TableSpoon::$weatherData[($k = $this->getLevel()->getId())])){
            $weather = TableSpoon::$weatherData[$k];
            $forever = ($this->getSide(Vector3::SIDE_DOWN)->getId() == Block::NETHERRACK);
            if(!$forever){
                if($weather->canCalculate()){
                    $rainy = ($weather->isRainy() || $weather->isRainyThunder());
                    if($rainy && (Utils::canSeeSky($this->getLevel(), $this->asVector3()) || Utils::canSeeSky($this->getLevel(), $this->getSide(Vector3::SIDE_NORTH)) || Utils::canSeeSky($this->getLevel(), $this->getSide(Vector3::SIDE_SOUTH)) || Utils::canSeeSky($this->getLevel(), $this->getSide(Vector3::SIDE_EAST)) || Utils::canSeeSky($this->getLevel(), $this->getSide(Vector3::SIDE_WEST)))){
                        $this->level->setBlock($this, BlockFactory::get(Block::AIR));
                    }
                }
            }
        }
    }
}
