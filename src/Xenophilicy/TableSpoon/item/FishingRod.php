<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\level\sound\LaunchSound;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Xenophilicy\TableSpoon\entity\projectile\FishingHook;
use Xenophilicy\TableSpoon\item\enchantment\Enchantment;
use Xenophilicy\TableSpoon\player\PlayerSession;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\Utils;
use Xenophilicy\TableSpoon\utils\FishingLootTable;

/**
 * Class FishingRod
 * @package Xenophilicy\TableSpoon\item
 */
class FishingRod extends Durable {
    /**
     * FishingRod constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct(Item::FISHING_ROD, $meta, "Fishing Rod");
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function getMaxDurability(): int{
        return 355;
    }
    
    public function onClickAir(Player $player, Vector3 $directionVector): bool{
        if(TableSpoon::$settings["player"]["fishing"]["enabled"]){
            $session = TableSpoon::getInstance()->getSessionById($player->getId());
            if($session instanceof PlayerSession){
                if(!$session->fishing){
                    $nbt = Entity::createBaseNBT($player->add(0, $player->getEyeHeight(), 0), $directionVector, $player->yaw, $player->pitch);
                    /** @var FishingHook $projectile */
                    $projectile = Entity::createEntity($this->getProjectileEntityType(), $player->getLevel(), $nbt, $player);
                    if($projectile !== null){
                        $projectile->setMotion($projectile->getMotion()->multiply($this->getThrowForce()));
                    }
                    if($projectile instanceof Projectile){
                        $projectileEv = new ProjectileLaunchEvent($projectile);
                        $projectileEv->call();
                        if($projectileEv->isCancelled()){
                            $projectile->flagForDespawn();
                        }else{
                            $projectile->spawnToAll();
                            $player->getLevel()->addSound(new LaunchSound($player), $player->getViewers());
                        }
                    }
                    $weather = TableSpoon::$weatherData[$player->getLevel()->getId()];
                    if(($weather->isRainy() || $weather->isRainyThunder())){
                        $rand = mt_rand(15, 50);
                    }else{
                        $rand = mt_rand(30, 100);
                    }
                    if($this->hasEnchantments()){
                        foreach(Utils::getEnchantments($this) as $enchantment){
                            switch($enchantment->getId()){
                                case Enchantment::LURE:
                                    $divisor = $enchantment->getLevel() * 0.50;
                                    $rand = intval(round($rand / $divisor)) + 3;
                                    break;
                            }
                        }
                    }
                    $projectile->attractTimer = $rand * 20;
                    $session->fishingHook = $projectile;
                    $session->fishing = true;
                }else{
                    $projectile = $session->fishingHook;
                    if($projectile instanceof FishingHook){
                        $session->unsetFishing();
                        if($player->getLevel()->getBlock($projectile->asVector3())->getId() == Block::WATER || $player->getLevel()->getBlock($projectile)->getId() == Block::WATER){
                            $damage = 5;
                        }else{
                            $damage = mt_rand(10, 15); // TODO: Implement entity / block collision properly
                        }
                        $this->applyDamage($damage);
                        if($projectile->coughtTimer > 0){
                            $weather = TableSpoon::$weatherData[$player->getLevel()->getId()];
                            $lvl = 0;
                            if($this->hasEnchantments()){
                                if($this->hasEnchantment(Enchantment::LUCK_OF_THE_SEA)){
                                    $lvl = $this->getEnchantment(Enchantment::LUCK_OF_THE_SEA)->getLevel();
                                }
                            }
                            if(($weather->isRainy() || $weather->isRainyThunder()) && $lvl == 0){
                                $lvl = 2;
                            }else{
                                $lvl = 0;
                            }
                            $item = FishingLootTable::getRandom($lvl);
                            $player->getInventory()->addItem($item);
                            $player->addXp(mt_rand(1, 6));
                        }
                    }
                }
            }
        }
        return true;
    }
    
    public function getProjectileEntityType(): string{
        return "FishingHook";
    }
    
    public function getThrowForce(): float{
        return 1.6;
    }
}