<?php

namespace skh6075\vanillaentities\entity\animal;


use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use skh6075\vanillaentities\entity\Animal;

class Sheep extends Animal{

    public $height = 1.3;
    public $width = 0.9;

    private bool $isRutting = false;


    public static function getNetworkTypeId(): string{
        return EntityIds::SHEEP;
    }

    public function getName(): string{
        return "Sheep";
    }

    protected function initEntity(CompoundTag $nbt): void{
        parent::initEntity($nbt);

        $this->isRutting = $nbt->hasTag("isRutting", ByteTag::class) ?
            ($nbt->getByte("isRutting") === 0 ? false : true) :
            false;
    }

    final public function onUpdate(int $currentTick): bool{
        $this->getEntityMovement()->getMovementUpdate()->onDefaultMovement();
        return parent::onUpdate($currentTick);
    }
}