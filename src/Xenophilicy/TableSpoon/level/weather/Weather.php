<?php
declare(strict_types=1);

namespace Xenophilicy\TableSpoon\level\weather;

use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class Weather
 * @package Xenophilicy\TableSpoon\level\weather
 */
class Weather {
    
    /** @var int */
    public const CLEAR = 0, SUNNY = 0, RAIN = 1, RAINY = 1, RAINY_THUNDER = 2, THUNDER = 3;
    
    private $level;
    private $weatherNow;
    private $strength1;
    private $strength2;
    private $duration;
    private $canCalculate = true;
    
    /** @var Vector3 */
    private $temporalVector;
    
    private $lastUpdate;
    
    private $randomWeatherData = [self::CLEAR, self::RAIN, self::RAINY_THUNDER];
    
    /**
     * Weather constructor.
     * @param Level $level
     * @param int $duration
     */
    public function __construct(Level $level, $duration = 1200){
        $this->level = $level;
        $this->weatherNow = self::SUNNY;
        $this->duration = $duration;
        $this->lastUpdate = $level->getServer()->getTick();
        $this->temporalVector = new Vector3(0, 0, 0);
    }
    
    /**
     * @param $weather
     * @return int
     */
    public static function getWeatherFromString($weather){
        if(is_int($weather)){
            if($weather <= 3){
                return $weather;
            }
            return -1;
        }
        switch(strtolower($weather)){
            case "clear":
            case "sunny":
            case "fine":
                return self::SUNNY;
            case "rain":
            case "rainy":
                return self::RAINY;
            case "thunder":
                return self::THUNDER;
            case "rain_thunder":
            case "rainy_thunder":
            case "storm":
                return self::RAINY_THUNDER;
            default:
                return -1;
        }
    }
    
    /**
     * @param bool $canCalc
     */
    public function setCanCalculate(bool $canCalc){
        $this->canCalculate = $canCalc;
    }
    
    /**
     * @param $currentTick
     */
    public function calcWeather($currentTick){
        if($this->canCalculate()){
            $tickDiff = $currentTick - $this->lastUpdate;
            $this->duration -= $tickDiff;
            if($this->duration <= 0){
                $duration = mt_rand(min(TableSpoon::$settings["weather"]["duration"]["minimum"], TableSpoon::$settings["weather"]["duration"]["maximum"]), max(TableSpoon::$settings["weather"]["duration"]["minimum"], TableSpoon::$settings["weather"]["duration"]["maximum"]));
                if($this->weatherNow === self::SUNNY){
                    $weather = array_rand($this->randomWeatherData);
                    $this->setWeather($weather, $duration);
                }else{
                    $weather = self::SUNNY;
                    $this->setWeather($weather, $duration);
                }
            }
            if(($this->weatherNow == self::RAINY_THUNDER or $this->weatherNow == self::THUNDER) and is_int($this->duration / 200)){
                $players = $this->level->getPlayers();
                if(count($players) > 0){
                    $p = array_rand($players);
                    if($p instanceof Player){
                        $x = $p->x + mt_rand(-64, 64);
                        $z = $p->z + mt_rand(-64, 64);
                        $y = $this->level->getHighestBlockAt((int)$x, (int)$z);
                        if(TableSpoon::$settings["weather"]["lightning"]){
                            $nbt = Entity::createBaseNBT(new Vector3($x, $y, $z));
                            $lightning = Entity::createEntity("Lightning", $this->level, $nbt);
                            $lightning->spawnToAll();
                        }
                    }
                    
                }
            }
            $this->lastUpdate = $currentTick;
        }
    }
    
    /**
     * @return bool
     */
    public function canCalculate(): bool{
        return $this->canCalculate;
    }
    
    /**
     * @param int $wea
     * @param int $duration
     */
    public function setWeather(int $wea, int $duration = 12000){
        $this->weatherNow = $wea;
        $this->strength1 = mt_rand(90000, 110000); //If we're clearing the weather, it doesn't matter what strength values we set
        $this->strength2 = mt_rand(30000, 40000);
        $this->duration = $duration;
        $this->sendWeatherToAll();
    }
    
    public function sendWeatherToAll(){
        foreach($this->level->getPlayers() as $player){
            $this->sendWeather($player);
        }
    }
    
    /**
     * @param Player $p
     */
    public function sendWeather(Player $p){
        $pks = [new LevelEventPacket(), new LevelEventPacket()];
        $pks[0]->evid = LevelEventPacket::EVENT_STOP_RAIN;
        $pks[0]->data = $this->strength1;
        $pks[1]->evid = LevelEventPacket::EVENT_STOP_THUNDER;
        $pks[1]->data = $this->strength2;
        switch($this->weatherNow){
            case self::RAIN:
                $pks[0]->evid = LevelEventPacket::EVENT_START_RAIN;
                $pks[0]->data = $this->strength1;
                break;
            case self::RAINY_THUNDER:
                $pks[0]->evid = LevelEventPacket::EVENT_START_RAIN;
                $pks[0]->data = $this->strength1;
                $pks[1]->evid = LevelEventPacket::EVENT_START_THUNDER;
                $pks[1]->data = $this->strength2;
                break;
            case self::THUNDER:
                $pks[1]->evid = LevelEventPacket::EVENT_START_THUNDER;
                $pks[1]->data = $this->strength2;
                break;
            default:
                break;
        }
        foreach($pks as $pk){
            $p->dataPacket($pk);
        }
    }
    
    /**
     * @return array
     */
    public function getRandomWeatherData(): array{
        return $this->randomWeatherData;
    }
    
    /**
     * @param array $randomWeatherData
     */
    public function setRandomWeatherData(array $randomWeatherData){
        $this->randomWeatherData = $randomWeatherData;
    }
    
    /**
     * @return bool
     */
    public function isSunny(): bool{
        if(!$this->canCalculate){
            return false;
        }
        return $this->getWeather() === self::SUNNY;
    }
    
    /**
     * @return int
     */
    public function getWeather(): int{
        if(!$this->canCalculate){
            return self::SUNNY;
        }
        return $this->weatherNow;
    }
    
    /**
     * @return bool
     */
    public function isRainy(): bool{
        if(!$this->canCalculate){
            return false;
        }
        return $this->getWeather() === self::RAINY;
    }
    
    /**
     * @return bool
     */
    public function isRainyThunder(): bool{
        if(!$this->canCalculate){
            return false;
        }
        return $this->getWeather() === self::RAINY_THUNDER;
    }
    
    /**
     * @return bool
     */
    public function isThunder(): bool{
        if(!$this->canCalculate){
            return false;
        }
        return $this->getWeather() === self::THUNDER;
    }
    
    /**
     * @return array
     */
    public function getStrength(): array{
        return [$this->strength1, $this->strength2];
    }
    
}