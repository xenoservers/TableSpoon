<?php

declare(strict_types=1);

/*
     (      (
     )\ )   )\ )    *   )
    (()/(  (()/(  ` )  /(
     /(_))  /(_))  ( )(_))
    (_))   (_))   (_(_())
    | |    |_ _|  |_   _|
    | |__   | |     | |
    |____| |___|    |_|

                            ``--://///////:--``                            
                       .:+osyyyhhhhhhhhhhhhhyyyso+:.                       
                   -/oyyhhhhhhhdddddddddddddhhhhhhhyyo/-                   
                -+syhhhhdddmmNNNNNMMMMMMMMNNNNmmdddhhhhys+-                
             ./syhhhhddmNNNMMMMMMMMMMMMMMMMMMMMMNNNmddhhhhys/.             
           .+yhhhhddmNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNmddhhhhy+.           
         .+yhhhddmNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNmddhhhy+.         
       `/yyhhddmNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNmddhhhs/`       
      .oyhhhddmNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNmdddhhyo.      
     -syhhdddmNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNmdddhhys-     
    :yhhhdddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmdddhhhy:    
   -yhhhdddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmdddhhhy-   
  `syhhddddmmmmmmdhyyyhmmmmmmmmmmmmmmmmmmmmmmmmmmmmmdyyhhdmmmmmmmdddhhys`  
  +yhhdddddmmmhs/:--/+hmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmo/--:/shdmmmddddhhy+  
 -yhhdddddddh+:--:ohdddddddddddddddddddddddddddddddddmdhs:--:/ydddddddhhy- 
 +yhhddddddy:--/shddddddddddddddddddddddddddddddddddddddmdy/---oddddddhhy+ 
`syhhdddddy--:sdddhyysssossyhdddddddddddddddddhhyssooosyhdddy/--+dddddhhys`
.yhhdddddd--oddyo/::::::::-::/oydddddddddddho/:::::::::-:/+yddy:.sdddddhhy.
-yhhdddddd+ydh/-----:/+++////:--+ddddddddd/:-:://+++++/-----/ddhosdddddhhy-
-yhhdddddddhyo-:::--+ddddddhhhysydddddddddoosyhhddddddh:-:::-+shdddddddhhy-
.yhhdddddyo+//+sy+:-sdddddddddddddddddddddddddddddddddd/:/syo///+yhddddhhy.
`syhhdhs++osyhmNm+/-ydddddddddddddddddddddddddddddddddh/:/yNNdyso++ohddhys`
 +yyso+oshdmmmNNh+/.-:/++ossyyyhhhhhhhhhhhhyyyysoo+/::-./+sNNNmmdhso++shy+ 
 -o+osshdmmmmmmmyo//+/::----...----------........----:///osmmmmmmmdhss++o- 
 ./ssyydmddddmmmyo:ymNNNNmmmddddhhhhhhhhhhhhhhddddmmmmmd/osmmmddddmdyyso/` 
`:osyyhhmmmmmmmhs+:ydmNNNNNNMMMMMMMMMMMMMMMMMMMNNNNNNmds:+shmmmmmmmdhyyso-`
`:oyyyhhhddddhhyo:--:/+syhdmmNNNNNNNMNNNNNNNNmmmdhyo/:-::/syhdddddhhhhyyo:`
`.+syyhhhhhhhyyo//+oo++//::::://////////////:::::://++ooo++syyhhhhhhhyys+.`
 `-/osyyyyyyys++yhysooooooooooooooooooooooooooooooooooosydy+osyyyyyyyys+-` 
   `.:+ooooo++shdddhysooooooooooooooooooooooooooooooosyhdddho++ossso+/-``  
      ``./ooyhdddddddhysoooooooooooooooooooooooooossyddddddddhso+/.```     
         `+yhhhdddddddddhyssoooooooooooooooooossyhhdddddddddhhhy+`         
           .+yhhhdddddddddddhyysssssooosssssyhhdddddddddddhhhy+.           
             ./syhhhdddddddddddddddhhhhhdddddddddddddddhhhys/.             
                -+syhhhhdddddddddddddddddddddddddddhhhhys+-                
                   -/oyyhhhhhhdddddddddddddddhhhhhhyyo/-                   
                       .:+osyyyhhhhhhhhhhhhhyyyso+:.                       
                            ``--://///////:--``                            
 */

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\LitPumpkin as PMLitPumpkin;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Xenophilicy\TableSpoon\entity\mob\IronGolem;
use Xenophilicy\TableSpoon\entity\mob\SnowGolem;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\utils\EntityUtils;

/**
 * Class LitPumpkin
 * @package Xenophilicy\TableSpoon\block
 */
class LitPumpkin extends PMLitPumpkin {
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        $parent = parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        if($player instanceof Player){
            $level = $this->getLevel();
            if(TableSpoon::$settings["entities"]["golem"]["snow"]["enabled"]){
                if(EntityUtils::checkSnowGolemStructure($this)[0]){
                    $level->setBlock($this, new Air());
                    $level->setBlock($this->subtract(0, 1), new Air());
                    $level->setBlock($this->subtract(0, 2), new Air());
                    $golem = Entity::createEntity(Entity::SNOW_GOLEM, $level, Entity::createBaseNBT($this));
                    if($golem instanceof SnowGolem){
                        $golem->spawnToAll();
                    }
                }
            }
            if(TableSpoon::$settings["entities"]["golem"]["iron"]["enabled"]){
                $check = EntityUtils::checkIronGolemStructure($this);
                if($check[0]){
                    switch($check[1]){
                        case "X":
                            $level->setBlock($this->subtract(1, 1, 0), new Air());
                            $level->setBlock($this->add(1, -1, 0), new Air());
                            break;
                        case "Z":
                            $level->setBlock($this->subtract(0, 1, 1), new Air());
                            $level->setBlock($this->add(0, -1, 1), new Air());
                            break;
                    }
                    $level->setBlock($this, new Air());
                    $level->setBlock($this->subtract(0, 1), new Air());
                    $level->setBlock($this->subtract(0, 2), new Air());
                    $golem = Entity::createEntity(Entity::IRON_GOLEM, $level, Entity::createBaseNBT($this));
                    if($golem instanceof IronGolem){
                        $golem->spawnToAll();
                    }
                }
            }
        }
        return $parent;
    }
}