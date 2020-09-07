<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\projectile;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\item\Item;
use pocketmine\math\RayTraceResult;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemActorPacket;
use pocketmine\Player;
use pocketmine\Server;
use Xenophilicy\TableSpoon\item\Trident;

/**
 * Class ThrownTrident
 * @package Xenophilicy\TableSpoon\entity\projectile
 */
class ThrownTrident extends Projectile {
    public const NETWORK_ID = self::TRIDENT;
    
    public $height = 0.35;
    public $width = 0.25;
    public $gravity = 0.10;
    
    protected $damage = 8;
    protected $age = 0;
    
    public function entityBaseTick(int $tickDiff = 1): bool{
        if($this->closed){
            return false;
        }
        
        $hasUpdate = parent::entityBaseTick($tickDiff);
        
        if($this->age > 1200){
            $this->flagForDespawn();
            $hasUpdate = true;
        }
        return $hasUpdate;
    }
    
    public function onCollideWithPlayer(Player $player): void{
        if($this->blockHit === null){
            return;
        }
        
        $item = Item::nbtDeserialize($this->namedtag->getCompoundTag(Trident::TAG_TRIDENT));
        
        $playerInventory = $player->getInventory();
        
        if($player->isSurvival() and !$playerInventory->canAddItem($item)){
            return;
        }
        
        $pk = new TakeItemActorPacket();
        $pk->eid = $player->getId();
        $pk->target = $this->getId();
        $this->server->broadcastPacket($this->getViewers(), $pk);
        
        if(!$player->isCreative()){
            $playerInventory->addItem(clone $item);
        }
        $this->flagForDespawn();
    }
    
    public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void{
        if($entityHit === $this->getOwningEntity()){
            return;
        }
        $this->applyGravity();
        parent::onHitEntity($entityHit, $hitResult);
        
        $pk = new PlaySoundPacket();
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->soundName = "item.trident.hit";
        $pk->volume = 1;
        $pk->pitch = 1;
        Server::getInstance()->broadcastPacket($this->getViewers(), $pk);
    }
    
    public function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void{
        parent::onHitBlock($blockHit, $hitResult);
        $pk = new PlaySoundPacket();
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->soundName = "item.trident.hit_ground";
        $pk->volume = 1;
        $pk->pitch = 1;
        Server::getInstance()->broadcastPacket($this->getViewers(), $pk);
    }
}
