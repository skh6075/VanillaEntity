<?php


namespace skh6075\vanillaentities\entity;


use pocketmine\entity\Living;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use skh6075\vanillaentities\entity\ai\EntityMovement;

abstract class EntityBase extends Living{

    /** @var float */
    public float $eyeHeight = 0.5;

    /** @var float */
    public $width = 1.0;
    /** @var float */
    public $height = 1.0;

    /** @var EntityMovement|null */
    protected ?EntityMovement $movement = null;


    protected function initEntity(CompoundTag $nbt): void{
        parent::initEntity($nbt);

        $this->setHealth($nbt->hasTag("Health", FloatTag::class) ? $nbt->getFloat("Health") : 10);
        $this->setMaxHealth($nbt->getInt("MaxHealth"));
        $this->setImmobile();
    }

    public function saveNBT(): CompoundTag{
        $nbt = parent::saveNBT();
        $nbt->setInt("MaxHealth", $this->getMaxHealth());
        return $nbt;
    }

    /**
     * @description Override
     *
     * @return int
     */
    public function getDefaultMaxHealth(): int{
        return 10;
    }

    /**
     * @description Control Entity Movement.
     *
     * @return EntityMovement
     */
    public function getEntityMovement(): EntityMovement{
        return $this->movement ?? new EntityMovement($this);
    }


}