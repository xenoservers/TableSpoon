<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\network;

use pocketmine\inventory\FurnaceRecipe;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\enchantment\EnchantmentList;
use pocketmine\item\Item;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\CraftingDataPacket as PMCraftingDataPacket;
use UnexpectedValueException;
use Xenophilicy\TableSpoon\item\enchantment\Enchantment;

/**
 * Class CraftingDataPacket
 * @package Xenophilicy\TableSpoon\network
 */
class CraftingDataPacket extends PMCraftingDataPacket {
    
    /** @var int */
    public const
      ENTRY_ENCHANT_LIST = 4, //TODO
      ENTRY_SHULKER_BOX = 5; //TODO
    
    protected function decodePayload(): void{
        $this->decodedEntries = [];
        $recipeCount = $this->getUnsignedVarInt();
        for($i = 0; $i < $recipeCount; ++$i){
            $entry = [];
            $entry["type"] = $recipeType = $this->getVarInt();
            switch($recipeType){
                case self::ENTRY_SHAPELESS:
                case self::ENTRY_SHULKER_BOX:
                    $ingredientCount = $this->getUnsignedVarInt();
                    /** @var Item */
                    $entry["input"] = [];
                    for($j = 0; $j < $ingredientCount; ++$j){
                        $entry["input"][] = $this->getSlot();
                    }
                    $resultCount = $this->getUnsignedVarInt();
                    $entry["output"] = [];
                    for($k = 0; $k < $resultCount; ++$k){
                        $entry["output"][] = $this->getSlot();
                    }
                    $entry["uuid"] = $this->getUUID()->toString();
                    break;
                case self::ENTRY_SHAPED:
                    $entry["width"] = $this->getVarInt();
                    $entry["height"] = $this->getVarInt();
                    $count = $entry["width"] * $entry["height"];
                    $entry["input"] = [];
                    for($j = 0; $j < $count; ++$j){
                        $entry["input"][] = $this->getSlot();
                    }
                    $resultCount = $this->getUnsignedVarInt();
                    $entry["output"] = [];
                    for($k = 0; $k < $resultCount; ++$k){
                        $entry["output"][] = $this->getSlot();
                    }
                    $entry["uuid"] = $this->getUUID()->toString();
                    break;
                case self::ENTRY_FURNACE:
                case self::ENTRY_FURNACE_DATA:
                    $entry["inputId"] = $this->getVarInt();
                    if($recipeType === self::ENTRY_FURNACE_DATA){
                        $entry["inputDamage"] = $this->getVarInt();
                    }
                    $entry["output"] = $this->getSlot();
                    break;
                case self::ENTRY_ENCHANT_LIST:
                    $entry["uuid"] = $this->getUUID()->toString();
                    break;
                default:
                    throw new UnexpectedValueException("Unhandled recipe type $recipeType!"); //do not continue attempting to decode
            }
            $this->decodedEntries[] = $entry;
        }
        $this->getBool(); //cleanRecipes
    }
    
    protected function encodePayload(): void{
        $this->putUnsignedVarInt(count($this->entries));
        $writer = new NetworkBinaryStream();
        foreach($this->entries as $d){
            $entryType = self::writeEntry($d, $writer);
            if($entryType >= 0){
                $this->putVarInt($entryType);
                $this->put($writer->getBuffer());
            }else{
                $this->putVarInt(-1);
            }
            $writer->reset();
        }
        
        $this->putUnsignedVarInt(count($this->potionTypeRecipes));
        foreach($this->potionTypeRecipes as $recipe){
            $this->putVarInt($recipe->getInputItemId());
            $this->putVarInt($recipe->getInputItemMeta());
            $this->putVarInt($recipe->getIngredientItemId());
            $this->putVarInt($recipe->getIngredientItemMeta());
            $this->putVarInt($recipe->getOutputItemId());
            $this->putVarInt($recipe->getOutputItemMeta());
        }
        $this->putUnsignedVarInt(count($this->potionContainerRecipes));
        foreach($this->potionContainerRecipes as $recipe){
            $this->putVarInt($recipe->getInputItemId());
            $this->putVarInt($recipe->getIngredientItemId());
            $this->putVarInt($recipe->getOutputItemId());
        }
    }
    
    /**
     * @param object $entry
     * @param NetworkBinaryStream $stream
     * @return int
     */
    private static function writeEntry($entry, NetworkBinaryStream $stream){
        if($entry instanceof ShapelessRecipe){
            return self::writeShapelessRecipe($entry, $stream);
        }elseif($entry instanceof ShapedRecipe){
            return self::writeShapedRecipe($entry, $stream);
        }elseif($entry instanceof FurnaceRecipe){
            return self::writeFurnaceRecipe($entry, $stream);
        }elseif($entry instanceof EnchantmentList){
            return self::writeEnchantList($entry, $stream);
        }
        return -1;
    }
    
    /**
     * @param ShapelessRecipe $recipe
     * @param NetworkBinaryStream $stream
     * @return int
     */
    private static function writeShapelessRecipe(ShapelessRecipe $recipe, NetworkBinaryStream $stream){
        $stream->putUnsignedVarInt($recipe->getIngredientCount());
        foreach($recipe->getIngredientList() as $item){
            $stream->putSlot($item);
        }
        $results = $recipe->getResults();
        $stream->putUnsignedVarInt(count($results));
        foreach($results as $item){
            $stream->putSlot($item);
        }
        $stream->put(str_repeat("\x00", 16)); //Null UUID
        return CraftingDataPacket::ENTRY_SHAPELESS;
    }
    
    /**
     * @param ShapedRecipe $recipe
     * @param NetworkBinaryStream $stream
     * @return int
     */
    private static function writeShapedRecipe(ShapedRecipe $recipe, NetworkBinaryStream $stream){
        $stream->putVarInt($recipe->getWidth());
        $stream->putVarInt($recipe->getHeight());
        for($z = 0; $z < $recipe->getHeight(); ++$z){
            for($x = 0; $x < $recipe->getWidth(); ++$x){
                $stream->putSlot($recipe->getIngredient($x, $z));
            }
        }
        $results = $recipe->getResults();
        $stream->putUnsignedVarInt(count($results));
        foreach($results as $item){
            $stream->putSlot($item);
        }
        $stream->put(str_repeat("\x00", 16)); //Null UUID
        return CraftingDataPacket::ENTRY_SHAPED;
    }
    
    /**
     * @param FurnaceRecipe $recipe
     * @param NetworkBinaryStream $stream
     * @return int
     */
    private static function writeFurnaceRecipe(FurnaceRecipe $recipe, NetworkBinaryStream $stream){
        if(!$recipe->getInput()->hasAnyDamageValue()){ //Data recipe
            $stream->putVarInt($recipe->getInput()->getId());
            $stream->putVarInt($recipe->getInput()->getDamage());
            $stream->putSlot($recipe->getResult());
            return CraftingDataPacket::ENTRY_FURNACE_DATA;
        }else{
            $stream->putVarInt($recipe->getInput()->getId());
            $stream->putSlot($recipe->getResult());
            return CraftingDataPacket::ENTRY_FURNACE;
        }
    }
    
    /**
     * @param EnchantmentList $list
     * @param NetworkBinaryStream $stream
     * @return int
     */
    private static function writeEnchantList(EnchantmentList $list, NetworkBinaryStream $stream){
        $stream->putByte($list->getSize());
        for($i = 0; $i < $list->getSize(); $i++){
            $entry = $list->getSlot($i);
            $stream->putUnsignedVarInt($entry->getCost());
            $stream->putUnsignedVarInt(count($entry->getEnchantments()));
            /** @var Enchantment $enchantment */
            foreach($entry->getEnchantments() as $enchantment){
                $stream->putUnsignedVarInt($enchantment->getId());
                $stream->putUnsignedVarInt(mt_rand(1, $enchantment->getMaxLevel()));
            }
            $stream->putString($entry->getRandomName());
        }
        return CraftingDataPacket::ENTRY_ENCHANT_LIST;
    }
}
