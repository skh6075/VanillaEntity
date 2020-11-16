<?php


namespace skh6075\vanillaentities;


use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
use skh6075\vanillaentities\entity\animal\Sheep;

class VanillaEntities extends PluginBase{


    protected function onLoad(): void{
        EntityFactory::getInstance()->register(Sheep::class, function (World $world, CompoundTag $nbt): Sheep{
            return new Sheep(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["Sheep"], EntityLegacyIds::SHEEP);
    }

    protected function onEnable(): void{
    }


}
