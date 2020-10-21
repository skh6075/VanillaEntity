<?php


namespace skh6075\vanillaentity\entity;

use pocketmine\entity\Living;

abstract class EntityBase extends Living{


    public function onUpdate (int $currentTick): bool{
        parent::onUpdate ($currentTick);
        return true;
    }
    
    public function jump (): void{
        parent::jump ();
    }
}