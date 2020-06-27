<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;

/**
 * Class Horse
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Horse extends Living {
    public const NETWORK_ID = self::HORSE;
    public $width = 2;
    public $height = 3;
    
    public function getName(): string{
        return "Horse";
    }
    
    /**
     * @param $id
     */
    public function setChestPlate($id){
        /*
        416, 417, 418, 419 only
        */
        $pk = new MobArmorEquipmentPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->slots = [Item::get(0, 0), Item::get($id, 0), Item::get(0, 0), Item::get(0, 0),];
        foreach($this->level->getPlayers() as $player){
            $player->dataPacket($pk);
        }
    }
    
    public function getDrops(): array{
        return $drops = [Item::get(Item::LEATHER, 0, mt_rand(0, 2)),];
    }
}
