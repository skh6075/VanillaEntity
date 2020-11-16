<?php


namespace skh6075\vanillaentities\entity;

use pocketmine\entity\Ageable;
use pocketmine\nbt\tag\CompoundTag;

abstract class Animal extends EntityBase implements Ageable{

    public const TYPE_ADULT = 0;
    public const TYPE_BABY = 1;

    protected bool $isBaby = true;


    public function isBaby(): bool{
        return $this->isBaby;
    }

    abstract public static function getNetworkTypeId(): string;

    abstract public function getName(): string;


    public function saveNBT(): CompoundTag{
        $nbt = parent::saveNBT();
        $nbt->setByte("isBaby", $this->isBaby() ? self::TYPE_BABY : self::TYPE_ADULT);
        return $nbt;
    }
}