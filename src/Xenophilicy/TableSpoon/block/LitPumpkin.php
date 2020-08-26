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

use pocketmine\block\Block;
use pocketmine\block\LitPumpkin as PMLitPumpkin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class LitPumpkin
 * @package Xenophilicy\TableSpoon\block
 */
class LitPumpkin extends PMLitPumpkin {
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
}