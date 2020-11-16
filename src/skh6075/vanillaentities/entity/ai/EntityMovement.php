<?php


namespace skh6075\vanillaentities\entity\ai;


use pocketmine\entity\Entity;
use skh6075\vanillaentities\entity\EntityBase;

final class EntityMovement{

    /** @var float */
    protected float $speed = 0.1;

    /** @var bool */
    protected bool $canJump = true;

    /** @var bool */
    protected bool $canCrossFence = false;

    /** @var bool */
    protected bool $canBreakBlock = false;

    /** @var Entity */
    protected Entity $entity;

    /** @var EntityMovementUpdate */
    protected EntityMovementUpdate $movementUpdate;


    public function __construct(Entity $entity) {
        $this->entity = $entity;
        $this->movementUpdate = new EntityMovementUpdate($this->entity);
    }

    public function setMovementSpeed(float $value = 0.1): void{
        $this->speed = $value;
    }

    public function getMovementSpeed(): float{
        return $this->speed;
    }

    public function isCanJump(): bool{
        return $this->canJump;
    }

    public function setCanJump(bool $value): void{
        $this->canJump = $value;
    }

    public function isCanCrossFence(): bool{
        return $this->canCrossFence;
    }

    public function setCanCrossFence(bool $value): void{
        $this->canCrossFence = $value;
    }

    public function isCanBreakBlock(): bool{
        return $this->canBreakBlock;
    }

    public function setCanBreakBlock(bool $value): void{
        $this->canBreakBlock = $value;
    }

    public function getMovementUpdate(): EntityMovementUpdate{
        return $this->movementUpdate;
    }
}