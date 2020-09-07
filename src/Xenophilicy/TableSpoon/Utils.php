<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\utils\Color;
use Xenophilicy\TableSpoon\block\Obsidian;
use Xenophilicy\TableSpoon\block\Portal;

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
    
    /**
     * @param $needle
     * @param $haystack
     * @return bool
     */
    public static function in_arrayi($needle, $haystack){
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
    
    /**
     * @param $level
     * @return int
     */
    public static function getDimension($level): int{
        if($level instanceof Level){
            if(TableSpoon::$settings["dimensions"]["nether"]["enabled"]){
                if($level->getName() == TableSpoon::$netherLevel->getName()){
                    return DimensionIds::NETHER;
                }
            }
            if(TableSpoon::$settings["dimensions"]["end"]["enabled"]){
                if($level->getName() == TableSpoon::$endLevel->getName()){
                    return DimensionIds::THE_END;
                }
            }
        }
        return DimensionIds::OVERWORLD;
    }
    
    /**
     * @param Position $pos
     * @param Level $level
     * @return Position
     */
    public static function genNetherSpawn(Position $pos, Level $level): Position{
        $x = (int)ceil($pos->getX() / 8);
        $y = (int)ceil($pos->getY());
        $z = (int)ceil($pos->getZ() / 8);
        $top = $level->getBlockAt($x, $y, $z);
        $bottom = $level->getBlockAt($x, $y - 1, $z);
        if(!self::checkBlock($top) || !self::checkBlock($bottom)){
            for($y = 125; $y >= 0; $y--){
                $top = $level->getBlockAt($x, $y, $z);
                $bottom = $level->getBlockAt($x, $y - 1, $z);
                if(self::checkBlock($top) && self::checkBlock($bottom)) break;
            }
            if($y <= 0){
                $y = mt_rand(10, 125);
            }
        }
        $pos = new Vector3($x, $y, $z);
        $obsidian = [[0, 0], [-1, 0], [-1, 1], [-1, 2], [-1, 3], [-1, 4], [0, 4], [1, 4], [2, 4], [2, 3], [2, 2], [2, 1], [2, 0], [1, 0]];
        foreach($obsidian as $add){
            $level->setBlock(new Vector3($pos->x + $add[0], $pos->y + $add[1], $pos->z), new Obsidian());
        }
        $portal = [[0, 1], [0, 2], [0, 3], [1, 1], [1, 2], [1, 3]];
        foreach($portal as $add){
            $level->setBlock(new Vector3($pos->x + $add[0], $pos->y + $add[1], $pos->z), new Portal());
        }
        for($x = -1; $x <= 2; $x++){
            for($z = -1; $z <= 1; $z++){
                $level->setBlock(new Vector3($pos->x + $x, $pos->y - 1, $pos->z + $z), new Obsidian());
            }
        }
        return new Position($pos->x - 1, $pos->y, $pos->z - 1, $level);
    }
    
    private static function checkBlock(Block $block): bool{
        if($block instanceof Air) return true;
        return false;
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
