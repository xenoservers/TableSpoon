<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Player;
use pocketmine\utils\Color;
use Xenophilicy\TableSpoon\utils\EntityUtils;

/**
 * Class Utils
 * @package Xenophilicy\TableSpoon
 */
class Utils {
    
    /**
     * @param Level $lvl
     * @param Vector3 $pos
     * @return bool
     */
    public static function canSeeSky(Level $lvl, Vector3 $pos){
        return ($lvl->getHighestBlockAt($pos->getFloorX(), $pos->getFloorZ()) <= $pos->getY());
    }
    
    /**
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @return float|int
     */
    public static function vector3XZDistance(Vector3 $pos1, Vector3 $pos2){
        return (($pos1->x - $pos2->x) + ($pos1->z - $pos2->z));
    }
    
    public static function getPotionColor(int $effectID): Color{
        return Potion::getPotionEffectsById($effectID)[0]->getColor();
    }
    
    public static function toggleBool(bool $boolean): bool{
        return !$boolean;
    }
    
    public static function boolToString(bool $boolean): string{
        return $boolean ? "true" : "false";
    }
    
    public static function isDelayedTeleportCancellable(Player $player, int $destinationDimension): bool{
        switch($destinationDimension){
            case DimensionIds::NETHER:
                return (!EntityUtils::isInsideOfPortal($player));
            case DimensionIds::THE_END:
                return (!EntityUtils::isInsideOfEndPortal($player));
            case DimensionIds::OVERWORLD:
                return (!EntityUtils::isInsideOfEndPortal($player) && !EntityUtils::isInsideOfPortal($player));
        }
        return false;
    }
    
    /**
     * @param $needle
     * @param $haystack
     * @return bool
     */
    public static function in_arrayi($needle, $haystack){
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
    
    public static function getDimension(Level $level): int{
        if(TableSpoon::$settings["dimensions"]["nether"]["enabled"] || TableSpoon::$settings["dimensions"]["end"]["enabled"]){
            if($level->getName() == TableSpoon::$netherLevel->getName()){
                return DimensionIds::NETHER;
            }elseif($level->getName() == TableSpoon::$endLevel->getName()){
                return DimensionIds::THE_END;
            }
        }
        return DimensionIds::OVERWORLD;
    }
    
    /**
     * @param $a
     * @param $b
     * @param $c
     * @return array|float[]|int[]
     */
    public static function solveQuadratic($a, $b, $c): array{
        $x[0] = (-$b + sqrt($b ** 2 - 4 * $a * $c)) / (2 * $a);
        $x[1] = (-$b - sqrt($b ** 2 - 4 * $a * $c)) / (2 * $a);
        if($x[0] == $x[1]){
            return [$x[0]];
        }
        return $x;
    }
    
    public static function stringToASCIIHex(string $string): string{
        $return = "";
        for($i = 0; $i < strlen($string); $i++){
            $return .= "\x" . bin2hex($string[$i]);
        }
        return $return;
    }
    
    /**
     * @param Item $item
     * @return EnchantmentInstance[]
     */
    public static function getEnchantments(Item $item): array{
        /** @var EnchantmentInstance[] $enchantments */
        $enchantments = [];
        
        $ench = $item->getNamedTagEntry(Item::TAG_ENCH);
        if($ench instanceof ListTag){
            /** @var CompoundTag $entry */
            foreach($ench as $entry){
                $id = $entry->getShort("id");
                $lvl = $entry->getShort("lvl");
                if($id > 26 || $lvl <= 0){
                    continue;
                }
                $e = Enchantment::getEnchantment($id);
                if($e !== null){
                    $enchantments[] = new EnchantmentInstance($e, $lvl);
                }
            }
        }
        return $enchantments;
    }
}
