<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\utils;


use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use Xenophilicy\TableSpoon\item\utils\FireworksData;
use Xenophilicy\TableSpoon\Utils;

/**
 * Class Firework
 * @package Xenophilicy\TableSpoon\utils
 */
class Firework extends Utils {
    /**
     * @param FireworksData $data
     * @return CompoundTag
     */
    public static function fireworkData2NBT(FireworksData $data){
        // https://github.com/thebigsmileXD/fireworks/blob/master/src/xenialdan/fireworks/item/Fireworks.php#L54-L74
        $value = [];
        $root = new CompoundTag();
        foreach($data->explosions as $explosion){
            $tag = new CompoundTag();
            $tag->setByteArray("FireworkColor", (string)$explosion->fireworkColor[0]);
            $tag->setByteArray("FireworkFade", (string)$explosion->fireworkFade[0]);
            $tag->setByte("FireworkFlicker", ($explosion->fireworkFlicker ? 1 : 0));
            $tag->setByte("FireworkTrail", ($explosion->fireworkTrail ? 1 : 0));
            $tag->setByte("FireworkType", $explosion->fireworkType);
            $value[] = $tag;
        }
        $explosions = new ListTag("Explosions", $value, NBT::TAG_Compound);
        $root->setTag(new CompoundTag("Fireworks", [$explosions, new ByteTag("Flight", $data->flight)]));
        return $root;
    }
}