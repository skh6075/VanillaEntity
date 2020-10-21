<?php


namespace skh6075\vanillaentity\entity\animal;

use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\level\particle\HeartParticle;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\item\Item;

use skh6075\vanillaentity\entity\AnimalEntity;

class Chicken extends AnimalEntity{

    const NETWORK_ID = EntityIds::CHICKEN;
    
    /** @var null|Player */
    private $findPlayer = null;
    
    public $width = 1;
    public $height = 0.8;
    
    protected $attraction = 0;
    protected $temptation = false;
    protected $isWound = false;
    protected $moveSpeed = 0.6;
    
    
    public function getName (): string{
        return "Chicken";
    }
    
    public function initEntity (): void{
        parent::initEntity ();
        
        if (!$this->namedtag->hasTag (self::HEALTH, FloatTag::class)) {
            $this->namedtag->setFloat (self::HEALTH, 4.0);
        }
        if (!$this->namedtag->hasTag (self::ATTRACTION, IntTag::class)) {
            $this->namedtag->setInt (self::ATTRACTION, 0);
        }
        $this->setHealth ($this->namedtag->getFloat (self::HEALTH));
        $this->setMaxHealth ($this->namedtag->getFloat (self::HEALTH));
        $this->attraction = $this->namedtag->getInt (self::ATTRACTION);
    }
    
    public function saveNBT (): void{
        parent::saveNBT ();
        
        $this->namedtag->setFloat (self::HEALTH, $this->getHealth ());
        $this->namedtag->setInt (self::ATTRACTION, $this->attraction);
    }
    
    public function setAttraction (int $attraction = 0): void{
        $this->attraction = $attraction;
    }
    
    public function onUpdate (int $currentTick): bool{
        if ($this->attraction > 0) {
            if ($this->isRutting ()) {
                if (($findEntity = $this->getEntityByPosInRadius ($this, 3.5)) instanceof Chicken) {
                    $findEntity->setRutting (0);
                    $findEntity->setAttraction (0);
                    $findEntity->level->addParticle (new HeartParticle ($findEntity->add (0.5, 0.7, 0.5)));
                    $this->setRutting (0);
                    $this->setAttraction (0);
                    $this->level->addParticle (new HeartParticle ($this->add (0.5, 0.7, 0.5)));
                    
                    $newEntity = Entity::createEntity ("Chicken", $this->level, Entity::createBaseNBT ($this->asVector3 ()));
                    $newEntity->spawnToAll ();
                    $newEntity->setScale (0.5);
                }
            } else {
                $this->attraction = 0;
            }
            $this->attraction --;
            if ($this->atrraction <= 0) {
                $this->atrraction = 0;
            }
        }
        if (!$this->isRutting ()) {
            if (!$this->findPlayer instanceof Player) {
                if (($findPlayer = $this->getPlayerByPosInRadius ($this, 7.5)) instanceof Player) {
                    $this->findPlayer = $findPlayer;
                }
            } else {
                if ($this->findPlayer instanceof Vector3 and $this instanceof Vector3) {
                    if ($this->findPlayer->level instanceof Level and $this instanceof Level) {
                        if ($this->findPlayer->level->getFolderName () === $this->level->getFolderName) {
                            $findWalk = false;
                            if ($this->temptation) {
                                if ($this->findPlayer->getInventory ()->getItemInHand ()->getId () === Item::SEEDS) {
                                    $findWalk = true;
                                } else {
                                    $this->temptation = false;
                                    $this->findPlayer = null;
                                }
                            } else {
                                if ($this->findPlayer->getInventory ()->getItemInHand ()->getId () === Item::SEEDS) {
                                    $this->temptation = true;
                                    $this->findPlayer = null;
                                }
                            }
                            $this->followWalking ($findWalk ? $this->findPlayer : null);
                        } else {
                            $this->findPlayer = null;
                        }
                    } else {
                        $this->findPlayer = null;
                    }
                } else {
                    $this->findPlayer = null;
                }
            }
        }
        return parent::onUpdate ($currentTick);
    }
    
    public function followWalking (?Player $player = null): void{
        if ($player instanceof Player) {
            if ($player->isOnline () and $this->isAlive ()) {
                $xOffset = $player->x - $this->x;
                $yOffset = $player->y - $this->y;
                $zOffset = $player->z - $this->z;
                if ($x ** 2 + $z ** 2 < 0.7) {
                    $this->motion->x = 0;
                    $this->motion->z = 0;
                } else {
                    $diff = abs ($x) + abs ($z);
                    $this->motion->x = $this->moveSpeed * 0.15 * ($x / $diff);
                    $this->motion->z = $this->moveSpeed * 0.15 * ($z / $diff);
                }
                $this->yaw = rad2deg (atan2 (-$x, $z));
                $this->pitch = rad2deg (atan (-$player->y));
                $this->move ($this->motion->x, $this->motion->y, $this->motion->z);
            }
        } else {
            $occrtion = [
                [ 5, -5 ],
                [ -5, 5 ],
                [ 5, 5 ],
                [ -5, -5 ]
            ];
            $rand = $occrtion [mt_rand (0, count ($occrtion) - 1)];
            
            $xOffset = $rand [0] - $this->x;
            $yOffset = $this->y;
            $zOffset = $rand [1] - $this->z;
            if ($x ** 2 + $z ** 2 < 0.7) {
                $this->motion->x = 0;
                $this->motion->z = 0;
            } else {
                $diff = abs ($x) + abs ($z);
                $this->motion->x = $this->moveSpeed * 0.15 * ($x / $diff);
                $this->motion->z = $this->moveSpeed * 0.15 * ($z / $diff);
            }
            $this->yaw = rad2deg (atan2 (-$x, $z));
            $this->pitch = rad2deg (atan (-$player->y));
            $this->move ($this->motion->x, $this->motion->y, $this->motion->z);
        }
    }
    
    private function getEntityByPosInRadius (Position $pos, float $radius): ?Entity{
        $result = null;
        $distance = 100;
        
        foreach ($pos->level->getEntities () as $entity) {
            if ($pos->distance ($entity) <= $radius) {
                $distance = $pos->distance ($entity);
                $result = $entity;
            }
        }
        return $result;
    }
    
    private function getPlayerByPosInRadius (Position $pos, float $radius): ?Player{
        $result = null;
        $distance = 100;
        
        foreach ($pos->level->getPlayers () as $player) {
            if ($pos->distance ($player) <= $radius) {
                $distance = $pos->distance ($player);
                $result = $player;
            }
        }
        return $result;
    }
}