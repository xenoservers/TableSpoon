<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\utils\Random;
use Xenophilicy\TableSpoon\entity\projectile\FireworkRocket;
use Xenophilicy\TableSpoon\player\PlayerSession;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\task\ElytraRocketBoostTrackingTask;

/**
 * Class Fireworks
 * @package Xenophilicy\TableSpoon\item
 */
class Fireworks extends Item{

    public const TAG_FIREWORKS = "Fireworks";
    public const TAG_EXPLOSIONS = "Explosions";
    public const TAG_FLIGHT = "Flight";

    /** @var float */
    public $spread = 5.0;

    /**
     * Fireworks constructor.
     * @param int $meta
     */
    public function __construct($meta = 0){
        parent::__construct(Item::FIREWORKS, $meta, "Fireworks");
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        if(TableSpoon::$settings["entities"]["fireworks"]){
            if($this->getNamedTag()->hasTag(self::TAG_FIREWORKS, CompoundTag::class)){
                $random = new Random();
                $yaw = $random->nextBoundedInt(360);
                $pitch = -1 * (float)(90 + ($random->nextFloat() * $this->spread - $this->spread / 2));
                $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), null, $yaw, $pitch);
                $tags = $this->getNamedTagEntry(self::TAG_FIREWORKS);
                if(!is_null($tags)){
                    $nbt->setTag($tags);
                }
                $level = $player->getLevel();
                $rocket = new FireworkRocket($level, $nbt, $player, $this, $random);
                $level->addEntity($rocket);
                if($rocket instanceof Entity){
                    if($player->isSurvival()){
                        --$this->count;
                    }
                    $rocket->spawnToAll();
                    return true;
                }
            }
        }
        return false;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool{
        if(TableSpoon::$settings["player"]["elytra"]["enabled"] && TableSpoon::$settings["player"]["elytra"]["boost"]){
            $session = TableSpoon::getInstance()->getSessionById($player->getId());
            if($session instanceof PlayerSession){
                if($session->usingElytra && !$player->isOnGround()){
                    if($player->getGamemode() != Player::CREATIVE && $player->getGamemode() != Player::SPECTATOR){
                        $this->pop();
                    }
                    $damage = 0;
                    $flight = 1;
                    if(TableSpoon::$settings["entities"]["fireworks"]){
                        if($this->getNamedTag()->hasTag(self::TAG_FIREWORKS, CompoundTag::class)){
                            $fwNBT = $this->getNamedTag()->getCompoundTag(self::TAG_FIREWORKS);
                            $flight = $fwNBT->getByte(self::TAG_FLIGHT);
                            $explosions = $fwNBT->getListTag(self::TAG_EXPLOSIONS);
                            if(count($explosions) > 0){
                                $damage = 7;
                            }
                        }
                    }
                    $dir = $player->getDirectionVector();
                    $player->setMotion($dir->multiply($flight * 1.25));
                    $player->getLevel()->broadcastLevelSoundEvent($player->asVector3(), LevelSoundEventPacket::SOUND_LAUNCH);
                    if(TableSpoon::$settings["player"]["elytra"]["particles"]){
                        TableSpoon::getInstance()->getScheduler()->scheduleRepeatingTask(new ElytraRocketBoostTrackingTask($player, 6), 4);
                    }

                    if($damage > 0){
                        $ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_CUSTOM, 7); // lets wait till PMMP Adds Fireworks damage constant
                        $player->attack($ev);
                    }
                }
            }
        }
        return true;
    }
}