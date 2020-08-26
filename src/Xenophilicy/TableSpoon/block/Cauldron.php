<?php

/**
 * MMP""MM""YMM               .M"""bgd
 * P'   MM   `7              ,MI    "Y
 *      MM  .gP"Ya   ,6"Yb.  `MMb.   `7MMpdMAo.  ,pW"Wq.   ,pW"Wq.`7MMpMMMb.
 *      MM ,M'   Yb 8)   MM    `YMMNq. MM   `Wb 6W'   `Wb 6W'   `Wb MM    MM
 *      MM 8M""""""  ,pm9MM  .     `MM MM    M8 8M     M8 8M     M8 MM    MM
 *      MM YM.    , 8M   MM  Mb     dM MM   ,AP YA.   ,A9 YA.   ,A9 MM    MM
 *    .JMML.`Mbmmd' `Moo9^Yo.P"Ybmmd"  MMbmmd'   `Ybmd9'   `Ybmd9'.JMML  JMML.
 *                                     MM
 *                                   .JMML.
 * This file is part of TableSpoon.
 * TableSpoon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * TableSpoon is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with TableSpoon.  If not, see <http://www.gnu.org/licenses/>.
 * @author Xenophilicy\TableSpoon & iTX Technologies
 * @link https://CortexPE.xyz
 */

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\block;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\item\TieredTool;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\utils\Color;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\tile\Cauldron as CauldronTile;
use Xenophilicy\TableSpoon\tile\Tile;
use Xenophilicy\TableSpoon\utils\DyeUtils;

/**
 * Class Cauldron
 * @package Xenophilicy\TableSpoon\block
 */
class Cauldron extends Transparent {
    
    protected $id = self::CAULDRON_BLOCK;
    protected $itemId = Item::CAULDRON;
    
    /**
     * Cauldron constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        $this->meta = $meta;
    }
    
    public function canBeActivated(): bool{
        return true;
    }
    
    public function getName(): string{
        return "Cauldron";
    }
    
    public function getHardness(): float{
        return 2;
    }
    
    public function getToolType(): int{
        return BlockToolType::TYPE_PICKAXE;
    }
    
    public function getToolHarvestLevel(): int{
        return TieredTool::TIER_WOODEN;
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool{
        Tile::createTile(Tile::CAULDRON, $this->getLevel(), CauldronTile::createNBT($this, $face, $item, $player));
        return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
    
    public function onActivate(Item $item, Player $player = null): bool{
        if(!TableSpoon::$settings["blocks"]["cauldrons"]){
            return false;
        }
        $tile = $this->getLevel()->getTile($this);
        if(!($tile instanceof CauldronTile)){
            return false;
        }
        switch($item->getId()){
            case Item::BUCKET:
                if($item->getDamage() == 0){ // empty bucket
                    if(!$this->isFull() or $tile->hasCustomColor() or $tile->hasPotion()){
                        break;
                    }
                    $bucket = clone $item;
                    $bucket->setDamage(8);
                    if($player->isSurvival()){
                        $player->getInventory()->setItemInHand($bucket);
                    }
                    $this->meta = 0;
                    //$this->getLevel()->setBlock($this, $this, true);
                    $tile->resetCustomColor();
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_TAKE_WATER);
                }elseif($item->getDamage() == 8){ // water bucket
                    if($this->isFull() and !$tile->hasCustomColor() and !$tile->hasPotion()){
                        break;
                    }
                    $bucket = clone $item;
                    $bucket->setDamage(0);
                    if($player->isSurvival()){
                        $player->getInventory()->setItemInHand($bucket);
                    }
                    if($tile->hasPotion()){
                        $tile->resetPotion();
                        $tile->setSplashPotion(false);
                        $tile->resetCustomColor();
                        $this->meta = 0;
                        //$this->getLevel()->setBlock($this, $this, true);
                        $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_EXPLODE);
                    }else{
                        $this->meta = 6;
                        //$this->getLevel()->setBlock($this, $this, true);
                        $tile->resetCustomColor();
                        $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_FILL_WATER);
                    }
                }
                break;
            case Item::DYE:
                if($tile->hasPotion()) break;
                $col = DyeUtils::getDyeColor($item->getDamage());
                $col->setA(127);
                if($tile->hasCustomColor()){
                    $color = Color::mix($tile->getCustomColor(), $col);
                }else{
                    $color = $col;
                }
                if($player->isSurvival()){
                    $item->pop();
                }
                $tile->setCustomColor($color);
                //$this->getLevel()->setBlock($this, $this, true);
                $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_ADD_DYE);
                break;
            case Item::LEATHER_CAP:
            case Item::LEATHER_TUNIC:
            case Item::LEATHER_PANTS:
            case Item::LEATHER_BOOTS:
                if($this->isEmpty() || $tile->hasPotion()) break;
                if($tile->hasCustomColor()){
                    --$this->meta;
                    //$this->getLevel()->setBlock($this, $this, true);
                    /** @var Armor $newItem */
                    $newItem = clone $item;
                    $newItem->setCustomColor($tile->getCustomColor());
                    $player->getInventory()->setItemInHand($newItem);
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_DYE_ARMOR);
                    if($this->isEmpty()){
                        $tile->resetCustomColor();
                    }
                }else{
                    --$this->meta;
                    //$this->getLevel()->setBlock($this, $this, true);
                    /** @var Armor $newItem */
                    $newItem = clone $item;
                    if($newItem->getNamedTag()->hasTag(Armor::TAG_CUSTOM_COLOR, IntTag::class)){
                        $newItem->getNamedTag()->removeTag(Armor::TAG_CUSTOM_COLOR);
                    }
                    $player->getInventory()->setItemInHand($newItem);
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_CLEAN_ARMOR);
                }
                break;
            case Item::POTION:
            case Item::SPLASH_POTION:
                if(!$this->isEmpty() && ( // basically.... mismatched potion ID with cauldron = BOOM!
                    ($tile->getPotionId() != $item->getDamage() && $item->getDamage() != 0) || ($item->getId() == Item::POTION && $tile->isSplashPotion()) || ($item->getId() == Item::SPLASH_POTION && !$tile->isSplashPotion()) && $item->getDamage() != 0 || ($item->getDamage() == 0 && $tile->hasPotion()))){
                    $this->meta = 0;
                    $tile->resetPotion();
                    $tile->setSplashPotion(false);
                    $tile->resetCustomColor();
                    if($player->isSurvival()){
                        $player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
                    }
                    //$this->getLevel()->setBlock($this, $this, true);
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_EXPLODE);
                }elseif($item->getDamage() == 0){
                    $this->meta += 2;
                    if($this->meta > 6){
                        $this->meta = 6;
                    }
                    if($player->isSurvival()){
                        $player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
                    }
                    $tile->resetPotion();
                    $tile->setSplashPotion(false);
                    $tile->resetCustomColor();
                    //$this->getLevel()->setBlock($this, $this, true);
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_FILL_WATER);
                }elseif(!$this->isFull()){
                    $this->meta += 2;
                    if($this->meta > 6){
                        $this->meta = 6;
                    }
                    $tile->setPotionId($item->getDamage());
                    $tile->setSplashPotion($item->getId() == Item::SPLASH_POTION);
                    $col = new Color(0, 0, 0, 0);
                    foreach(Potion::getPotionEffectsById($item->getDamage()) as $effect){
                        $col = Color::mix($effect->getColor(), $col);
                    }
                    $tile->setCustomColor($col);
                    //$this->getLevel()->setBlock($this, $this, true);
                    if($player->isSurvival()){
                        $player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
                    }
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_TAKE_POTION);
                }
                break;
            case Item::GLASS_BOTTLE:
                if($this->meta < 2){
                    break;
                }
                if($tile->hasPotion()){
                    $this->meta -= 2;
                    if($tile->isSplashPotion()){
                        $result = Item::get(Item::SPLASH_POTION, $tile->getPotionId());
                    }else{
                        $result = Item::get(Item::POTION, $tile->getPotionId());
                    }
                    if($this->isEmpty()){
                        $tile->resetPotion();
                        $tile->setSplashPotion(false);
                        $tile->resetCustomColor();
                    }
                    //$this->getLevel()->setBlock($this, $this, true);
                    $item->pop();
                    if(($inv = $player->getInventory())->canAddItem($result)){
                        $inv->addItem($result);
                    }else{
                        $this->getLevel()->dropItem($player, $result);
                    }
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_TAKE_POTION);
                }else{
                    $this->meta -= 2;
                    //$this->getLevel()->setBlock($this, $this, true);
                    if($player->isSurvival()){
                        $result = Item::get(Item::POTION, 0);
                        $item->pop();
                        if(($inv = $player->getInventory())->canAddItem($result)){
                            $inv->addItem($result);
                        }else{
                            $this->getLevel()->dropItem($player, $result);
                        }
                    }
                    $this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_CAULDRON_TAKE_WATER);
                }
                break;
        }
        // hack to fix cauldron updates... wtf
        $this->meta += 3;
        $this->getLevel()->setBlock($this, $this, true);
        $this->meta -= 3;
        $this->getLevel()->setBlock($this, $this, true);
        return true;
    }
    
    public function isFull(): bool{
        return $this->meta >= 6;
    }
    
    public function isEmpty(): bool{
        return $this->meta == 0;
    }
}