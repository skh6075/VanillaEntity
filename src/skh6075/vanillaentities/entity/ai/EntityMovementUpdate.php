<?php


namespace skh6075\vanillaentities\entity\ai;

use pocketmine\entity\Entity;
use pocketmine\world\Position;
use skh6075\vanillaentities\entity\EntityBase;

final class EntityMovementUpdate{

    protected Entity $entity;


    public function __construct(Entity $entity) {
        $this->entity = $entity;
    }

    private function getLookPositionInLength(int $length): Position{
        $yaw = deg2rad($this->entity->getLocation()->getYaw());
        return new Position(
            $this->entity->getPosition()->getX() + $length * -sin($yaw),
            $this->entity->getEyeHeight(),
            $this->entity->getPosition()->getZ() + $length * cos($yaw)
        );
    }

    /**
     * @return int[][]
     */
    private function getRandomValues(): array{
        return [
            [-5, -5],
            [-5, 5],
            [5, -5],
            [5, 5]
        ];
    }


    /**
     * @description This source is an AI source under development.
     *              Don't use Override yet and wait.
     *              getMotion, setMotion isn't soft yet.
     *              Also, there was a big error in using the rocket-launcher method.
     */
    public function onDefaultMovement(): void{
        $values = $this->getRandomValues() [mt_rand(0, count($this->getRandomValues()) - 1)];

        /** @var int $xOffset */
        $xOffset = $values[0] - $this->entity->getPosition()->getX();
        /** @var int $yOffset */
        $yOffset = $this->entity->getPosition()->getY();
        /** @var int $zOffset */
        $zOffset = $values[2] - $this->entity->getPosition()->getZ();

        $speed = 0.1;
        if ($this->entity instanceof EntityBase) {
            $speed = $this->entity->getEntityMovement()->getMovementSpeed();
        }
        if (($xOffset ** 2) + ($zOffset ** 2) < ($speed * 0.15)) {
            $this->entity->getMotion()->x = 0;
            $this->entity->getMotion()->z = 0;
        } else {
            $diff = abs($xOffset) + abs($zOffset);
            $this->entity->getMotion()->x = $speed * 0.15 * ($xOffset / $diff);
            $this->entity->getMotion()->z = $speed * 0.15 * ($zOffset / $diff);
            $this->entity->getLocation()->yaw = rad2deg(atan2(-$xOffset, $zOffset));
        }
        $this->entity->getLocation()->pitch = rad2deg(atan(-$yOffset));
        /** @description Reduce the mobility of all motion. */
        $this->entity->setMotion($this->entity->getMotion());
    }
}