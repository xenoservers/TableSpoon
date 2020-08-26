<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

/**
 * Class BlockManager
 * @package Xenophilicy\TableSpoon\block
 */
class BlockManager {
    public static function init(): void{
        BlockFactory::registerBlock(new Portal(), true);
        BlockFactory::registerBlock(new EndPortal(), true);
        BlockFactory::registerBlock(new Obsidian(), true);
        BlockFactory::registerBlock(new DragonEgg(), true);
        BlockFactory::registerBlock(new Beacon(), true);
        BlockFactory::registerBlock(new Fire(), true);
        BlockFactory::registerBlock(new Bed(), true);
        BlockFactory::registerBlock(new SlimeBlock(), true);
        BlockFactory::registerBlock(new EndPortalFrame(), true);
        BlockFactory::registerBlock(new Lava(), true);
        BlockFactory::registerBlock(new StillLava(), true);
        BlockFactory::registerBlock(new FrostedIce(), true);
        BlockFactory::registerBlock(new ShulkerBox(Block::UNDYED_SHULKER_BOX), true);
        BlockFactory::registerBlock(new ShulkerBox(), true);
        BlockFactory::registerBlock(new Hopper(), true);
        BlockFactory::registerBlock(new EnchantingTable(), true);
        BlockFactory::registerBlock(new Anvil(), true);
        BlockFactory::registerBlock(new Pumpkin(), true);
        BlockFactory::registerBlock(new LitPumpkin(), true);
        BlockFactory::registerBlock(new SnowLayer(), true);
        BlockFactory::registerBlock(new BrewingStand(), true);
        BlockFactory::registerBlock(new Rail(), true);
        BlockFactory::registerBlock(new Cauldron(), true);
        //BlockFactory::registerBlock(new Jukebox(), true);
        BlockFactory::registerBlock(new Sponge(), true);
    }
}
