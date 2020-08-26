<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\item\{Item, ItemFactory};
use Xenophilicy\TableSpoon\TableSpoon;


/**
 * Class ItemManager
 * @package Xenophilicy\TableSpoon\item
 */
class ItemManager {
    public static function init(){
        ItemFactory::registerItem(new Boat(), true);
        ItemFactory::registerItem(new LingeringPotion(), true);
        ItemFactory::registerItem(new FireCharge(), true);
        ItemFactory::registerItem(new Elytra(), true);
        ItemFactory::registerItem(new Fireworks(), true);
        ItemFactory::registerItem(new FishingRod(), true);
        ItemFactory::registerItem(new EyeOfEnder(), true);
        ItemFactory::registerItem(new Bow(), true);
        ItemFactory::registerItem(new EndCrystal(), true);
        ItemFactory::registerItem(new Bucket(), true);
        ItemFactory::registerItem(new ArmorStand(), true);
        if(TableSpoon::$settings["entities"]["minecarts"]){
            ItemFactory::registerItem(new Minecart(), true);
        }
        //ItemFactory::registerItem(new Lead(), true);
        ItemFactory::registerItem(new BlazeRod(), true);
        ItemFactory::registerItem(new DragonBreath(), true);
        ItemFactory::registerItem(new GlassBottle(), true);
        ItemFactory::registerItem(new EnchantedBook(), true);
        ItemFactory::registerItem(new Trident(), true);
        ItemFactory::registerItem(new ShulkerBox(), true);
        ItemFactory::registerItem(new UnDyedShulkerBox(), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_13, 0, "Music Disc 13"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_CAT, 0, "Music Disc cat"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_BLOCKS, 0, "Music Disc blocks"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_CHIRP, 0, "Music Disc chirp"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_FAR, 0, "Music Disc far"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_MALL, 0, "Music Disc mall"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_MELLOHI, 0, "Music Disc mellohi"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_STAL, 0, "Music Disc stal"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_STRAD, 0, "Music Disc strad"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_WARD, 0, "Music Disc ward"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_11, 0, "Music Disc 11"), true);
        //ItemFactory::registerItem(new Record(Item::RECORD_WAIT, 0, "Music Disc wait"), true);
        Item::initCreativeItems();
    }
}
