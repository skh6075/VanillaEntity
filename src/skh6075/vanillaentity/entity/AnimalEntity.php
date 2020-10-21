<?php


namespace skh6075\vanillaentity\entity;

use pocketmine\nbt\tag\IntTag;

class AnimalEntity extends EntityBase implements VanillaEntityNameTag{

    /** @var int */
    private $rutting = 0;
    
    
    public function initEntity (): void{
        parent::initEntity ();
        
        if (!$this->namedtag->hasTag (self::RUTTING, IntTag::class)) {
            $this->namedtag->setInt (self::RUTTING, 0);
        }
        $this->rutting = $this->namedtag->getInt (self::RUTTING);
    }
    
    public function saveNBT (): void{
        parent::saveNBT ();
        
        $this->namedtag->setInt (self::RUTTING, $this->rutting);
    }
    
    public function getName (): string{
        return "AnimalEntity";
    }
    
    /**
     * @return bool
     */
    public function isRutting (): bool{
        return $this->rutting === 1;
    }
    
    /**
     * @param int $rutting
     */
    public function setRutting (int $rutting): void{
        $this->rutting = $rutting;
    }
}