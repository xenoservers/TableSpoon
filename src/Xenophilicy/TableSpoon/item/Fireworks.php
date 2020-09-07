<?php
declare(strict_types=1);

// Huge thanks to BlockHorizon's Fireworks plugin for the working fireworks functionality

namespace Xenophilicy\TableSpoon\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\utils\Random;
use Xenophilicy\TableSpoon\player\PlayerSession;
use Xenophilicy\TableSpoon\TableSpoon;
use Xenophilicy\TableSpoon\task\ElytraRocketBoostTrackingTask;

/**
 * Class Fireworks
 * @package Xenophilicy\TableSpoon\item
 */
class Fireworks extends Item {
    
    public const TAG_FIREWORKS = "Fireworks";
    public const TAG_FLIGHT = "Flight";
    public const TAG_EXPLOSIONS = "Explosions";
    
    public const TYPE_SMALL_SPHERE = 0;
    public const TYPE_HUGE_SPHERE = 1;
    public const TYPE_STAR = 2;
    public const TYPE_CREEPER_HEAD = 3;
    public const TYPE_BURST = 4;
    
    public const COLOR_BLACK = "\x00";
    public const COLOR_RED = "\x01";
    public const COLOR_DARK_GREEN = "\x02";
    public const COLOR_BROWN = "\x03";
    public const COLOR_BLUE = "\x04";
    public const COLOR_DARK_PURPLE = "\x05";
    public const COLOR_DARK_AQUA = "\x06";
    public const COLOR_GRAY = "\x07";
    public const COLOR_DARK_GRAY = "\x08";
    public const COLOR_PINK = "\x09";
    public const COLOR_GREEN = "\x0a";
    public const COLOR_YELLOW = "\x0b";
    public const COLOR_LIGHT_AQUA = "\x0c";
    public const COLOR_DARK_PINK = "\x0d";
    public const COLOR_GOLD = "\x0e";
    public const COLOR_WHITE = "\x0f";
    
    public function __construct(int $meta = 0){
        parent::__construct(self::FIREWORKS, $meta, "Fireworks");
    }
    
    public function getRandomizedFlightDuration(): int{
        return ($this->getFlightDuration() + 1) * 10 + mt_rand(0, 5) + mt_rand(0, 6);
    }
    
    public function getFlightDuration(): int{
        return $this->getExplosionsTag()->getByte(self::TAG_FLIGHT, 1);
    }
    
    protected function getExplosionsTag(): CompoundTag{
        return $this->getNamedTag()->getCompoundTag(self::TAG_FIREWORKS) ?? new CompoundTag(self::TAG_FIREWORKS);
    }
    
    public function setFlightDuration(int $duration): void{
        $tag = $this->getExplosionsTag();
        $tag->setByte(self::TAG_FLIGHT, $duration);
        $this->setNamedTagEntry($tag);
    }
    
    public function onClickAir(Player $player, Vector3 $directionVector): bool{
        if(!TableSpoon::$settings["player"]["elytra"]["enabled"] || !TableSpoon::$settings["player"]["elytra"]["boost"]) return false;
        $session = TableSpoon::getInstance()->getSessionById($player->getId());
        if(!$session instanceof PlayerSession) return false;
        if(!$session->usingElytra || $player->isOnGround()) return false;
        if(!$player->isCreative() && !$player->isSpectator()) $this->pop();
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
            $ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, $damage);
            $player->attack($ev);
        }
        return true;
    }
    
    public function addExplosion(int $type, string $color, string $fade = "", bool $flicker = false, bool $trail = false): void{
        $explosion = new CompoundTag();
        $explosion->setByte("FireworkType", $type);
        $explosion->setByteArray("FireworkColor", $color);
        $explosion->setByteArray("FireworkFade", $fade);
        $explosion->setByte("FireworkFlicker", $flicker ? 1 : 0);
        $explosion->setByte("FireworkTrail", $trail ? 1 : 0);
        $tag = $this->getExplosionsTag();
        $explosions = $tag->getListTag(self::TAG_EXPLOSIONS) ?? new ListTag(self::TAG_EXPLOSIONS);
        $explosions->push($explosion);
        $tag->setTag($explosions);
        $this->setNamedTagEntry($tag);
    }
    
    //    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
    //        if(!TableSpoon::$settings["entities"]["fireworks"]) return false;
    //        if(!$this->getNamedTag()->hasTag(self::TAG_FIREWORKS, CompoundTag::class)) return false;
    //        $random = new Random();
    //        $pitch = -1 * (float)(90 + ($random->nextFloat() * $this->spread - $this->spread / 2));
    //        $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), null, $yaw, $pitch);
    //        $tags = $this->getNamedTagEntry(self::TAG_FIREWORKS);
    //        if(!is_null($tags)){
    //            $nbt->setTag($tags);
    //        }
    //        $level = $player->getLevel();
    //        $rocket = new FireworkRocket($level, $nbt, $player, $this, $random);
    //        $level->addEntity($rocket);
    //        if(!$rocket instanceof Entity) return false;
    //        if($player->isSurvival()){
    //            --$this->count;
    //        }
    //        $rocket->spawnToAll();
    //        return true;
    //    }
    
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool{
        $random = new Random();
        $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), new Vector3(0.001, 0.05, 0.001), $random->nextBoundedInt(360), 90);
        $entity = Entity::createEntity("FireworkRocket", $player->getLevel(), $nbt, $this);
        if($entity instanceof Entity){
            if($player->isSurvival()) --$this->count;
            $entity->spawnToAll();
            return true;
        }
        var_dump("it was a big false");
        return false;
    }
}