<?php


namespace skh6075\vanillaentity;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;

use skh6075\vanillaentity\entity\animal\Chicken;

class VanillaEntity extends PluginBase{


    public function onLoad (): void{
        if (date_default_timezone_get () !== "Asia/Seoul") {
            date_default_timezone_set ("Asia/Seoul");
        }
        Entity::registerEntity (Chicken::class, true, [ "Chicken", "minecraft:chicken" ]);
    }
    
    public function onEnable (): void{
    }
    
}